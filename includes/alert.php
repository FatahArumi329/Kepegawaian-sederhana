<?php
function showAlert($type, $message) {
    $_SESSION['alert'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Function to display the alert and then clear it
function displayAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        $type = $alert['type'];
        $message = $alert['message'];
        
        $icon = '';
        $color = '';
        
        switch($type) {
            case 'error':
                $icon = 'exclamation-circle';
                $color = 'red';
                break;
            case 'success':
                $icon = 'check-circle';
                $color = 'green';
                break;
            case 'warning':
                $icon = 'exclamation-triangle';
                $color = 'yellow';
                break;
            case 'info':
                $icon = 'information-circle';
                $color = 'blue';
                break;
        }
        
        echo <<<HTML
        <div id="alert-modal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-{$color}-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-{$icon} text-{$color}-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {$type}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {$message}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="closeAlert()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('alert-modal').style.display = 'block';
            function closeAlert() {
                document.getElementById('alert-modal').style.display = 'none';
            }
        </script>
        HTML;
        
        unset($_SESSION['alert']);
    }
}
?>
