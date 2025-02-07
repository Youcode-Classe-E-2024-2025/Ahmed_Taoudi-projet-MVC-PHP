<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup | Task Flow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg,rgb(59, 246, 234) 0%,rgb(30, 175, 112) 100%);
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .checkbox:checked {
            background-color: #10B981; /* Emerald green */
            border-color: #10B981;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="card p-8">
            <div class="text-center mb-8">
                <!-- Icon -->
                <i class="fas fa-database text-5xl text-green-500 mb-4"></i>
                <!-- Title -->
                <h2 class="text-3xl font-bold text-gray-800">Database Setup</h2>
                <!-- Subtitle -->
                <p class="text-gray-500 mt-2">Configure your Task Flow database</p>
            </div>

            <form action="/" method="POST" class="space-y-6">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <!-- Info Alert -->
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded" role="alert">
                    <p class="text-green-700">
                        <i class="fas fa-info-circle mr-2 text-gray-500"></i>
                        Your database does not exist. Would you like to create it?
                    </p>
                </div>

                <!-- Populate with Sample Data Checkbox -->
                <div class="flex items-center justify-center space-x-4">
                    <label for="checkbox" class="flex items-center cursor-pointer">
                        <input 
                            id="checkbox" 
                            name="checkbox" 
                            type="checkbox" 
                            class="form-checkbox h-5 w-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500"
                        >
                        <span class="ml-3 text-gray-700 font-medium">
                            Populate with Sample Data
                        </span>
                    </label>
                </div>

                <!-- Create Database Button -->
                <div class="text-center">
                    <button 
                        type="submit" 
                        name="createdb"
                        value="CREATE_DB" 
                        class="w-full px-6 py-3 bg-green-600 text-white rounded-lg 
                        hover:bg-green-700 transition duration-300 ease-in-out 
                        focus:outline-none focus:ring-2 focus:ring-green-500 
                        focus:ring-opacity-50 flex items-center justify-center space-x-2 font-semibold"
                    >
                        <i class="fas fa-plus-circle"></i>
                        <span>Create Database</span>
                    </button>
                </div>
            </form>

            <!-- Footer Note -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>
                    <i class="fas fa-shield-alt mr-2"></i>
                    Secure database initialization
                </p>
            </div>
        </div>
    </div>
</body>
</html>