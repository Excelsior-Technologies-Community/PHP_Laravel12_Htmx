<div id="addTaskModal" class="fixed inset-0 modal-overlay z-50 flex items-center justify-center hidden">
    <div class="modal-box bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold"><i class="fas fa-plus-circle text-blue-500 mr-2"></i>Add New Task</h3>
            <button onclick="closeAddTaskModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form hx-post="/tasks"
              hx-target="#task-list"
              hx-swap="innerHTML"
              hx-on::after-request="this.reset(); closeAddTaskModal()"
              hx-headers='{"X-CSRF-TOKEN":"{{ csrf_token() }}"}'
              class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" required
                       class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2"
                          class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" class="w-full border p-2 rounded-lg">
                        <option value="general">General</option>
                        <option value="work">Work</option>
                        <option value="personal">Personal</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select name="priority" class="w-full border p-2 rounded-lg">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                <input type="date" name="due_date" class="w-full border p-2 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2.5 rounded-lg transition font-medium">
                <i class="fas fa-plus mr-2"></i> Add Task
            </button>
        </form>
    </div>
</div>