<div>

    <!-- Header / Navigation Bar -->
    <header class="bg-gray-800  text-white p-4 shadow-lg">
        <nav class="container mx-auto flex flex-col lg:flex-row px-[100px]  lg:items-start  justify-between items-center">
            <!-- Logo -->
            <a href="#" class="text-3xl  text-green-400 font-['poppins']"> PictureBin</a>

            <!-- Navigation Links --> 
            <div class="flex space-x-6">
                <a href="#"
                    class="text-gray-300 hover:text-white transition duration-300 ease-in-out font-medium">Home</a>
                <a href="#"
                    class="text-gray-300 hover:text-white transition duration-300 ease-in-out font-medium">My Gallery</a>
            </div>
        </nav>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow flex items-center justify-center p-4 h-[90vh] bg-repeat " style="background-image: url('{{ asset('images/15364609_5590897.jpg') }}') ; opacity: ; ">
       
       <!-- React App will be mounted here -->
        <div id="react-app-root" class="w-full h-full flex items-center justify-center ">
            <!-- The React component will render inside this div -->
        </div>
        {{-- <div class="bg-white  rounded-lg shadow-[0_15px_30px_rgba(0,0,0,0.5)] p-8 max-w-md w-full
                    hover:border-green-700 transition-all duration-300 ease-in-out
                    flex flex-col items-center justify-center text-center"
            style="min-height: 180px;">
            <!-- File Upload/Paste Area -->
            <p class="text-gray-700 text-xl mb-2 border-5 border-dashed p-12 border-green-500 rounded-lg">
                Paste files here or
                <button
                    class="text-green-600 hover:text-green-700 font-semibold underline cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition duration-200 ease-in-out">
                    Browse
                </button>
            </p>
            <input type="file" id="fileInput" class="hidden" multiple>
        </div> --}}
    </main>





    
    {{-- Care about people's approval and you will be their prisoner. --}}
</div>

<!-- React and ReactDOM CDN -->
    <script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
    <!-- Babel for JSX transformation in the browser (for development only) -->
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

  <!-- Your React Component Code -->
    <script type="text/babel">
        // Main App component
        function App() {
            const [selectedFiles, setSelectedFiles] = React.useState([]);
            const fileInputRef = React.useRef(null);

            const handleFileChange = (e) => {
                const files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
                processFiles(Array.from(files));
            };

            const processFiles = (newFiles) => {
                const filesToProcess = newFiles.map(file => {
                    return new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.onloadend = () => {
                            resolve({
                                file: file,
                                preview: reader.result,
                                id: URL.createObjectURL(file)
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                });

                Promise.all(filesToProcess).then(processedFiles => {
                    const uniqueNewFiles = processedFiles.filter(
                        (newFile) => !selectedFiles.some((existingFile) => existingFile.id === newFile.id)
                    );
                    setSelectedFiles((prevFiles) => [...prevFiles, ...uniqueNewFiles]);
                });
            };

            const handleDrop = (e) => {
                e.preventDefault();
                e.stopPropagation();
                e.currentTarget.classList.remove('border-green-500', 'border-dashed');
                handleFileChange(e);
            };

            const handleDragOver = (e) => {
                e.preventDefault();
                e.stopPropagation();
                e.currentTarget.classList.add('border-green-500', 'border-dashed');
            };

            const handleDragLeave = (e) => {
                e.stopPropagation();
                e.currentTarget.classList.remove('border-green-500', 'border-dashed');
            };

            const handleBrowseClick = () => {
                fileInputRef.current.click();
            };

            const handleDeleteImage = (idToDelete) => {
                setSelectedFiles((prevFiles) =>
                    prevFiles.filter((file) => file.id !== idToDelete)
                );
            };

            return (
                <div className="min-h-full w-full flex items-center justify-center p-4 font-sans antialiased">
                    <div className="bg-white p-8 rounded-xl shadow-[0_15px_30px_rgba(0,0,0,0.5)] w-full max-w-4xl">
                        <h1 className="text-3xl font-bold text-gray-800 mb-6 text-center">Image Uploader</h1>

                        {/* Drop zone for files */}
                        <div
                            className="border-2 border-gray-300 border-dotted rounded-lg p-6 mb-8 text-center cursor-pointer transition-all duration-300 hover:border-blue-500"
                            onDrop={handleDrop}
                            onDragOver={handleDragOver}
                            onDragLeave={handleDragLeave}
                            onClick={handleBrowseClick}
                        >
                            <input
                                type="file"
                                multiple
                                accept="image/*"
                                onChange={handleFileChange}
                                ref={fileInputRef}
                                className="hidden"
                            />
                            <p className="text-gray-600 text-lg">
                                Drag & Drop your images here, or <span className="text-blue-600 font-semibold cursor-pointer">Browse</span>
                            </p>
                            <p className="text-sm text-gray-500 mt-1">(Click or drag files onto this area)</p>
                        </div>

                        {/* Display selected image previews */}
                        {selectedFiles.length > 0 && (
                            <div className="mb-8">
                                <h2 className="text-xl font-semibold text-gray-700 mb-4">Selected Images:</h2>
                                <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                    {selectedFiles.map((fileData) => (
                                        <div
                                            key={fileData.id}
                                            className="relative group bg-gray-50 rounded-lg overflow-hidden shadow-md transition-all duration-300 hover:shadow-lg"
                                        >
                                            <img
                                                src={fileData.preview}
                                                alt={fileData.file.name}
                                                className="w-full h-32 object-cover rounded-t-lg"
                                                onError={(e) => { e.target.onerror = null; e.target.src="https://placehold.co/128x128/cccccc/444444?text=No+Preview"; }}
                                            />
                                            {/* Delete button */}
                                            <button
                                                onClick={() => handleDeleteImage(fileData.id)}
                                                className="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 w-6 h-6 flex items-center justify-center text-sm font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-200 focus:outline-none focus:ring-2 focus:ring-red-400"
                                                title="Delete image"
                                            >
                                                X
                                            </button>
                                            <div className="p-2 text-sm text-gray-700 truncate">
                                                {fileData.file.name}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {/* Upload Button (placeholder, actual upload logic not included) */}
                        {selectedFiles.length > 0 && (
                            <div className="text-center">
                                <button className="bg-blue-600 text-white px-8 py-3 rounded-xl shadow-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">
                                    Upload Images ({selectedFiles.length})
                                </button>
                            </div>
                        )}

                        {selectedFiles.length === 0 && (
                            <p className="text-center text-gray-500 mt-4">No images selected yet.</p>
                        )}
                    </div>
                </div>
            );
        }

        // Render the React component into the 'react-app-root' div
        const container = document.getElementById('react-app-root');
        const root = ReactDOM.createRoot(container);
        root.render(<App />);
    </script>