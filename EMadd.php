<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee | Personnel System</title>
<link href="<?= base_url()?>public/css/style.css" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-6 selection:bg-blue-100">

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Functional Data Mapping
    $data = [
        'name'       => trim($_POST['name']),
        'department' => trim($_POST['department']),
        'position'   => trim($_POST['position']),
        'salary'     => trim($_POST['salary']),
    ];

    // Database Operation
    $res = db()->table('employees')->insert($data);
    if ($res) {
        header('Location: ' . url('/EMdash'));
        exit;
    }
}
?>

<div class="w-full max-w-lg">
    <a href="<?= url('/EMdash') ?>" class="inline-flex items-center text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors mb-6 uppercase tracking-widest">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Directory
    </a>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-10">
        <header class="mb-10">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">New Personnel</h1>
            <p class="text-slate-500 text-sm mt-1">Register a new employee into the system.</p>
        </header>

        <form action="<?= url('/EMadd') ?>" method="post" class="space-y-6">
            
            <div class="group">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Employee Name</label>
                <input type="text" name="name" required
                    class="w-full px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/5 outline-none transition-all duration-200 text-sm"
                    placeholder="e.g. Joana Daniela">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Department</label>
                    <input type="text" name="department" required
                        class="w-full px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/5 outline-none transition-all duration-200 text-sm"
                        placeholder="IT / HR / Admin">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Position</label>
                    <input type="text" name="position" required
                        class="w-full px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/5 outline-none transition-all duration-200 text-sm"
                        placeholder="Lead Developer">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Monthly Salary (₱)</label>
                <div class="relative">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">₱</span>
                    <input type="number" name="salary" required
                        class="w-full pl-10 pr-5 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/5 outline-none transition-all duration-200 text-sm font-mono"
                        placeholder="0.00">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" 
                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98] tracking-wide">
                    Confirm Registration
                </button>
            </div>
        </form>
    </div>

    <footer class="mt-8 text-center">
        <p class="text-slate-400 text-[10px] uppercase tracking-[0.4em] font-medium">Internal System Access Only</p>
    </footer>
</div>

</body>
</html>