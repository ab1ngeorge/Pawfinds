import os

# Define the directory to list files and folders
directory = r'C:/xampp/htdocs/pet'

# Check if the directory exists
if os.path.exists(directory):
    # List all files and folders in the directory
    items = os.listdir(directory)
    
    print("Files and folders in", directory)
    for item in items:
        print(item)
else:
    print(f"The directory {directory} does not exist.")
