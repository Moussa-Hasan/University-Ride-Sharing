<dialog id="logout_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg"></h3>
        <p class="py-4">Are you sure you want to logout?</p>
        <div class="modal-action">
            <button onclick="logout()" class="btn btn-sm bg-red-500 text-white hover:bg-red-400">
                Yes, I'm sure
            </button>
            <form method="dialog">
                <button class="btn btn-sm bg-gray-500 text-white hover:bg-gray-400">
                    No, cancel
                </button>
            </form>
        </div>
    </div>
</dialog>