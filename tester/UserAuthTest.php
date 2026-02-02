<?php

class UserAuthTest extends TestCase
{
    public function testGenerateSQL()
    {
        $sql = UserAuth::generateSQL();
        $this->assertTrue(strpos($sql, 'CREATE TABLE IF NOT EXISTS users') !== false, "SQL does not contain CREATE TABLE. SQL was: $sql");
        $this->assertTrue(strpos($sql, 'email') !== false && strpos($sql, 'VARCHAR') !== false, "SQL does not contain email and VARCHAR. SQL was: $sql");
        $this->assertTrue(strpos($sql, 'inviteCode') !== false, "SQL does not contain inviteCode. SQL was: $sql");
    }

    public function testSignUpAndSignIn()
    {
        // Setup database
        $db = new Database();
        $db->query('DROP TABLE IF EXISTS users');

        // Use the generated SQL but adapt for SQLite
        // SQLite doesn't support backticks in all cases or some MySQL types,
        // but it usually handles VARCHAR and INT fine.
        // AUTO_INCREMENT -> AUTOINCREMENT
        $sql = UserAuth::generateSQL();
        $sql = str_replace('AUTO_INCREMENT', 'AUTOINCREMENT', $sql);
        // SQLite INTEGER PRIMARY KEY AUTOINCREMENT must be INTEGER, not INT
        $sql = str_replace('id INT AUTOINCREMENT PRIMARY KEY', 'id INTEGER PRIMARY KEY AUTOINCREMENT', $sql);

        $db->query($sql);

        $details = [
            'name'       => 'John Doe',
            'username'   => 'johndoe1234', // min 10
            'email'      => 'test@gmail.com', // should end with gmail.com
            'isCompany'  => 0, // boolean
            'password'   => 'password123', // min 8
            'inviteCode' => '123456', // equal to one of allowed
        ];

        $result = UserAuth::signUp($details);
        $this->assertEquals('User successfully registered.', $result, "SignUp failed: $result");

        $loginResult = UserAuth::signIn(['email' => 'test@gmail.com', 'password' => 'password123']);
        $this->assertEquals('User Found', $loginResult);
        $this->assertTrue(UserAuth::checkUser());

        UserAuth::logout();
        $this->assertFalse(UserAuth::checkUser());
    }
}
