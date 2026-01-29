<?php

class MakeDbCommand extends Command
{
    public function __construct()
    {
        parent::__construct('make:db', 'Create a new DB File');
    }

    public function execute($args)
    {
        $action = $args['1'] ?? readline("1- What's the DB file for (create, delete, addFieldTo, truncate, rename, modify, removeColumn): ");
        if (!in_array($action, ['create', 'delete', 'addFieldTo', 'truncate', 'rename', 'modify', 'removeColumn'])) {
            Terminal::error('Invalid action!');

            return;
        }

        $table = $args['2'] ?? readline("2- What's table name? ");
        if (!$table) {
            Terminal::error('Table name is required!');

            return;
        }

        $timestamp = date('Y_m_d_H_i_s');
        $filename = "{$action}{$table}Table_{$timestamp}.sql";
        $filePath = DB_FOLDER.'/'.$filename;

        $sqlTemplate = match ($action) {
            'create'       => "CREATE TABLE IF NOT EXISTS $table (\n    id INT PRIMARY KEY AUTO_INCREMENT,\n    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n);\n",
            'delete'       => "DROP TABLE IF EXISTS $table;\n",
            'addFieldTo'   => "ALTER TABLE $table ADD COLUMN new_field VARCHAR(255);\n",
            'truncate'     => "TRUNCATE TABLE $table;\n",
            'rename'       => "RENAME TABLE $table TO new_name;\n",
            'modify'       => "ALTER TABLE $table MODIFY COLUMN column_name VARCHAR(255);\n",
            'removeColumn' => "ALTER TABLE $table DROP COLUMN column_name;\n",
            default        => ''
        };

        if (file_put_contents($filePath, $sqlTemplate)) {
            Terminal::success('DB file created: '.Terminal::color($filename, 'cyan'));
        } else {
            Terminal::error('Could not create DB file!');
        }
    }
}

$registry->register(new MakeDbCommand());
