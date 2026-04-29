<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen p-8">

<?php
  // FETCH DATA
  try {
      $rows = db()->table('employees')->get_all();
  } catch (Exception $e) {
      $rows = [];
  }
?>

<div class="max-w-6xl mx-auto">
    <div id="notif" class="hidden text-sm text-center mb-4 p-3 rounded-lg shadow-sm"></div>

    <header class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <p class="text-slate-500 text-sm italic">Manage employees</p>
        </div>
        <div class="flex gap-3">
            <button onclick="openModal('addModal')" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
               + Add Employee
            </button>
            <a href="<?= url('/logout') ?>" class="px-5 py-2 border bg-white rounded-lg hover:bg-gray-50 transition">
               Logout
            </a>
        </div>
    </header>

    <input type="text" id="search" placeholder="Search employees..." class="w-full mb-4 p-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">

    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b text-xs font-bold uppercase text-slate-400">
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Department</th>
                    <th class="px-6 py-4">Position</th>
                    <th class="px-6 py-4">Salary</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php foreach($rows as $row): ?> 
                <tr class="border-b hover:bg-blue-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-blue-600">#<?= esc($row['id']) ?></td>
                    <td class="px-6 py-4 font-medium"><?= esc($row['name']) ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= esc($row['department']) ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= esc($row['position']) ?></td>
                    <td class="px-6 py-4 font-mono">₱<?= number_format(esc($row['salary']), 2) ?></td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <button 
                            onclick="openUpdateModal(<?= htmlspecialchars(json_encode($row)) ?>)"
                            class="text-blue-600 font-bold hover:underline">
                            Edit
                        </button>

                        <button 
                            class="text-red-500 font-bold hover:underline deleteBtn"
                            data-url="<?= url('/delete/'.$row['id']) ?>">
                            Delete
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr id="noResults" class="hidden"><td colspan="6" class="p-10 text-center text-slate-400 italic">No results found.</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div id="addModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-10 relative">
        <button onclick="closeModal('addModal')" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">&times;</button>
        <h2 class="text-2xl font-bold mb-2">New Personnel</h2>
        <p class="text-slate-500 text-sm mb-8">Register a new employee into the system.</p>
        
        <form action="<?= url('/EMadd') ?>" method="post" class="space-y-5" id="addForm">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:border-blue-600 outline-none transition">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Department</label>
                    <input type="text" name="department" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:border-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Position</label>
                    <input type="text" name="position" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:border-blue-600 outline-none transition">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Monthly Salary (₱)</label>
                <input type="number" name="salary" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:border-blue-600 outline-none transition font-mono">
            </div>
            <button type="submit" class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">Confirm Registration</button>
        </form>
    </div>
</div>

<div id="updateModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-10 relative">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-600"></div>
        <button onclick="closeModal('updateModal')" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">&times;</button>
        <h2 class="text-2xl font-bold mb-2">Modify Record</h2>
        <p class="text-slate-500 text-sm mb-8">Update employee details for ID: <span id="updateIdDisplay" class="font-bold text-blue-600"></span></p>
        
        <form id="updateForm" method="post" class="space-y-5">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Full Name</label>
                <input type="text" name="name" id="upd_name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:border-blue-600 outline-none transition">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Department</label>
                    <input type="text" name="department" id="upd_dept" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:border-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Position</label>
                    <input type="text" name="position" id="upd_pos" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:border-blue-600 outline-none transition">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Salary (₱)</label>
                <input type="number" name="salary" id="upd_sal" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:border-blue-600 outline-none transition font-mono">
            </div>
            <button type="submit" class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition">Save Changes</button>
        </form>
    </div>
</div>

<div id="deleteModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-xl shadow-lg text-center max-w-sm">
        <p class="mb-4 text-slate-600">Are you sure you want to delete this record?</p>
        <div class="flex justify-center gap-3">
            <button id="confirmDeleteBtn" class="bg-red-500 text-white px-6 py-2 rounded-lg font-bold">Yes, Delete</button>
            <button onclick="closeModal('deleteModal')" class="bg-gray-200 px-6 py-2 rounded-lg font-bold">Cancel</button>
        </div>
    </div>
</div>

<script>
// --- MODAL ENGINE ---
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}

// --- UPDATE LOGIC: Inject data into Modal ---
function openUpdateModal(data) {
    document.getElementById('updateIdDisplay').innerText = '#' + data.id;
    document.getElementById('upd_name').value = data.name;
    document.getElementById('upd_dept').value = data.department;
    document.getElementById('upd_pos').value = data.position;
    document.getElementById('upd_sal').value = data.salary;
    
    // Set form action dynamically
    document.getElementById('updateForm').action = "<?= url('/EMupdate/') ?>" + data.id;
    
    openModal('updateModal');
}

// --- DELETE LOGIC ---
let deleteUrl = "";
document.querySelectorAll(".deleteBtn").forEach(btn => {
    btn.addEventListener("click", function() {
        deleteUrl = this.dataset.url;
        openModal('deleteModal');
    });
});

document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
    if(deleteUrl) window.location.href = deleteUrl;
});

// --- LIVE SEARCH ---
document.getElementById("search").addEventListener("input", function() {
    let q = this.value.toLowerCase();
    let rows = document.querySelectorAll("#tableBody tr:not(#noResults)");
    let found = false;
    rows.forEach(r => {
        let match = r.innerText.toLowerCase().includes(q);
        r.style.display = match ? "" : "none";
        if(match) found = true;
    });
    document.getElementById("noResults").classList.toggle("hidden", found);
});
</script>
</body>
</html>