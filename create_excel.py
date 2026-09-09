import openpyxl
from openpyxl.styles import Font, PatternFill, Alignment, Border, Side

wb = openpyxl.Workbook()
ws = wb.active
ws.title = 'نتائج الفحص'

# Styles
header_font = Font(bold=True, color='FFFFFF', size=12)
header_fill = PatternFill(start_color='FF0000', end_color='FF0000', fill_type='solid')
cell_alignment = Alignment(vertical='top', wrap_text=True)
thin_border = Border(
    left=Side(style='thin'),
    right=Side(style='thin'),
    top=Side(style='thin'),
    bottom=Side(style='thin')
)

# Headers - Arabic
headers = ['#', 'الخطورة', 'الثغرة', 'الملف', 'السطر', 'الوصف', 'التأثير', 'الإصلاح', 'الحالة']
for col, header in enumerate(headers, 1):
    cell = ws.cell(row=1, column=col, value=header)
    cell.font = header_font
    cell.fill = header_fill
    cell.alignment = Alignment(horizontal='center', vertical='center', wrap_text=True)
    cell.border = thin_border

# Column widths
ws.column_dimensions['A'].width = 5
ws.column_dimensions['B'].width = 12
ws.column_dimensions['C'].width = 40
ws.column_dimensions['D'].width = 30
ws.column_dimensions['E'].width = 10
ws.column_dimensions['F'].width = 60
ws.column_dimensions['G'].width = 50
ws.column_dimensions['H'].width = 50
ws.column_dimensions['I'].width = 12

# All 15 findings in Arabic
findings = [
    {
        'id': 1, 'severity': 'عالي',
        'vulnerability': 'حقن SQL في دالة signIn()',
        'file': 'core/functions/PHP/classes/UserAuth.php', 'line': '107-114',
        'description': 'مفاتيح المقدمة من المستخدم كانت تُستخدم مباشرة في استعلام SQL بدون تحقق.',
        'impact': 'تجاوز المصادقة، تسريب البيانات، التلاعب بقاعدة البيانات',
        'fix': 'تحقق القائمة البيضاء مقابل أسماء الأعمدة المسموحة', 'status': 'تم الإصلاح'
    },
    {
        'id': 2, 'severity': 'عالي',
        'vulnerability': 'تجاوز المسار في الراوتر',
        'file': 'core/functions/PHP/getPage.php', 'line': '198',
        'description': 'معلمة الصفحة كانت تُستخدم مباشرة في file_exists() بدون تنقية.',
        'impact': 'قراءة ملفات عشوائية، الكشف عن الكود المصدري، تنفيذ تعليمات برمجية',
        'fix': 'تحقق regex للسماح بالأحرف والأرقام فقط', 'status': 'تم الإصلاح'
    },
    {
        'id': 3, 'severity': 'عالي',
        'vulnerability': 'ثغرة SSRF في Webhook',
        'file': 'core/functions/PHP/classes/Webhook.php', 'line': '27-47',
        'description': 'عنوان URL لم يكن يُتحقق منه مقابل نطاقات IP الداخلية/الخاصة.',
        'impact': 'الوصول للخدمات الداخلية، سرقة بيانات التعريف السحابية، فحص المنافذ',
        'fix': 'فرض HTTPS، تحليل DNS، تصفية نطاقات IP الخاصة', 'status': 'تم الإصلاح'
    },
    {
        'id': 4, 'severity': 'متوسط',
        'vulnerability': 'غياب رؤوس الأمان',
        'file': '.htaccess', 'line': 'غير محدد',
        'description': 'لم تكن هناك رؤوس أمان مُكوّنة.',
        'impact': 'Clickjacking, MIME sniffing, XSS amplification',
        'fix': 'أضفنا رؤوس أمان شاملة في .htaccess', 'status': 'تم الإصلاح'
    },
    {
        'id': 5, 'severity': 'متوسط',
        'vulnerability': 'تثبيت الجلسة',
        'file': 'core/functions/PHP/classes/UserAuth.php', 'line': '119',
        'description': 'لم يتم تجديد معرف الجلسة بعد تسجيل الدخول.',
        'impact': 'اختطاف الجلسة، الاستيلاء على الحساب',
        'fix': 'أضفنا session_regenerate_id(true)', 'status': 'تم الإصلاح'
    },
    {
        'id': 6, 'severity': 'عالي',
        'vulnerability': 'ملفات تعريف ارتباط غير آمنة',
        'file': 'core/functions/PHP/classes/CookieManager.php', 'line': '24',
        'description': 'ملفات تعريف الارتباط لم تكن تحتوي على HttpOnly و Secure و SameSite.',
        'impact': 'سرقة الكوكيز عبر XSS، هجمات CSRF',
        'fix': 'أضفنا HttpOnly + Secure + SameSite=Strict', 'status': 'تم الإصلاح'
    },
    {
        'id': 7, 'severity': 'عالي',
        'vulnerability': 'تشفير الجلسات ضعيف (base64)',
        'file': 'core/functions/PHP/classes/Session.php', 'line': '89',
        'description': 'التشفير المستخدم كان base64 فقط وليس تشفيراً حقيقياً.',
        'impact': 'تسريب بيانات الجلسة الحساسة',
        'fix': 'استبدلنا base64 بـ AES-256-CBC', 'status': 'تم الإصلاح'
    },
    {
        'id': 8, 'severity': 'عالي',
        'vulnerability': 'تجاوز المسار في الجلسات',
        'file': 'core/functions/PHP/classes/Session.php', 'line': '38',
        'description': 'مفتاح الجلسة لم يكن يُنقى ليسمح باستخدام ../',
        'impact': 'كتابة ملفات عشوائية، تنفيذ تعليمات برمجية',
        'fix': 'أضفنا تنقية للمفتاح بـ regex', 'status': 'تم الإصلاح'
    },
    {
        'id': 9, 'severity': 'متوسط',
        'vulnerability': 'تسريب معلومات حساسة في رسائل الخطأ',
        'file': 'core/functions/PHP/classes/Database.php', 'line': '54',
        'description': 'رسالة خطأ قاعدة البيانات كانت تكشف التفاصيل.',
        'impact': 'الكشف عن مسارات الملفات، أسماء الجداول، بيانات الاعتماد',
        'fix': 'أضفنا error_log داخلي + رسالة عامة', 'status': 'تم الإصلاح'
    },
    {
        'id': 10, 'severity': 'متوسط',
        'vulnerability': 'مفتاح تشفير APP_KEY مفقود',
        'file': '.env.example', 'line': 'غير محدد',
        'description': 'لم يكن هناك متغير APP_KEY في ملف البيئة.',
        'impact': 'فك تشفير البيانات الحساسة، التلاعب بالجلسات',
        'fix': 'أضفنا APP_KEY إلى .env.example', 'status': 'تم الإصلاح'
    },
    {
        'id': 11, 'severity': 'عالي',
        'vulnerability': 'تجاوز المسار في Language.php',
        'file': 'core/functions/PHP/classes/Language.php', 'line': '39',
        'description': 'كود اللغة لم يكن يُنقى، مما يسمح بتجاوز المسار لقراءة ملفات JSON عشوائية.',
        'impact': 'قراءة ملفات تعريب عشوائية، الكشف عن معلومات',
        'fix': 'أضفنا تنقية لكود اللغة بـ regex', 'status': 'تم الإصلاح'
    },
    {
        'id': 12, 'severity': 'عالي',
        'vulnerability': 'تجاوز المتغيرات في extract() بالقوالب',
        'file': 'core/functions/PHP/classes/AhmedTemplate.php', 'line': '39',
        'description': 'extract() كانت تُستخدم بدون حماية، مما يسمح بتجاوز المتغيرات الداخلية.',
        'impact': 'تجاوز متغيرات النظام، تسريب معلومات',
        'fix': 'أضفنا EXTR_SKIP لمنع الكتابة فوق المتغيرات الموجودة', 'status': 'تم الإصلاح'
    },
    {
        'id': 13, 'severity': 'متوسط',
        'vulnerability': 'غياب Content Security Policy',
        'file': '.htaccess', 'line': 'غير محدد',
        'description': 'لم يكن هناك رأس CSP لمنع XSS وحقن المحتوى.',
        'impact': 'XSS، حقن CSS/JS، data exfiltration',
        'fix': 'أضفنا Content-Security-Policy header', 'status': 'تم الإصلاح'
    },
    {
        'id': 14, 'severity': 'متوسط',
        'vulnerability': 'تسريب أخطاء SQL في executeSQLFilePDO',
        'file': 'core/functions/PHP/executeSQLFilePDO.php', 'line': '50-52',
        'description': 'رسائل خطأ SQL كانت تُعرض للمستخدم مع تفاصيل قاعدة البيانات.',
        'impact': 'الكشف عن مخطط قاعدة البيانات، بيانات الاعتماد',
        'fix': 'أضفنا error_log داخلي + رسائل عامة', 'status': 'تم الإصلاح'
    },
    {
        'id': 15, 'severity': 'عالي',
        'vulnerability': 'DOM-based XSS في redirect.js',
        'file': 'public/JS/redirect.js', 'line': '52',
        'description': 'innerHTML كان يُستخدم مباشرة لتحديث DOM بدون تنقية.',
        'impact': 'تنفيذ JavaScript عشوائي، سرقة الجلسات',
        'fix': 'استبدلنا innerHTML بـ DOMParser', 'status': 'تم الإصلاح'
    }
]

severity_colors = {
    'حرج': 'FF0000',
    'عالي': 'FF6666',
    'متوسط': 'FFAAAA',
    'منخفض': 'FFCCCC'
}

for row_idx, finding in enumerate(findings, 2):
    ws.cell(row=row_idx, column=1, value=finding['id']).alignment = cell_alignment
    ws.cell(row=row_idx, column=1).border = thin_border
    
    sev_cell = ws.cell(row=row_idx, column=2, value=finding['severity'])
    sev_cell.alignment = cell_alignment
    sev_cell.border = thin_border
    sev_cell.fill = PatternFill(start_color=severity_colors.get(finding['severity'], 'FFFFFF'), end_color=severity_colors.get(finding['severity'], 'FFFFFF'), fill_type='solid')
    
    for col, key in enumerate(['vulnerability', 'file', 'line', 'description', 'impact', 'fix', 'status'], 3):
        cell = ws.cell(row=row_idx, column=col, value=finding[key])
        cell.alignment = cell_alignment
        cell.border = thin_border

# Summary sheet
ws2 = wb.create_sheet('الملخص')
ws2.cell(row=1, column=1, value='ملخص فحص الأمان').font = Font(bold=True, size=14)
ws2.cell(row=2, column=1, value='تاريخ الفحص: 2026-09-08')
ws2.cell(row=3, column=1, value='إجمالي الثغرات: 15')
ws2.cell(row=4, column=1, value='حرج: 0')
ws2.cell(row=5, column=1, value='عالي: 8')
ws2.cell(row=6, column=1, value='متوسط: 7')
ws2.cell(row=7, column=1, value='منخفض: 0')
ws2.cell(row=8, column=1, value='تم الإصلاح: 15/15')

wb.save('Security_Report_v2.xlsx')
print('✅ تم تحديث ملف Excel: Security_Findings_Report.xlsx')
