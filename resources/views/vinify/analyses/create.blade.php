<x-app-layout>
    <style>
        .plagiarized {
            background-color: #fbbf24;
            /* jaune */
            color: black;
            font-weight: bold;
            cursor: pointer;
            padding: 1px 3px;
            border-radius: 3px;
            text-decoration: underline;
        }
    </style>

    <div class="py-10 lg:py-14">
        <!-- Title -->
        <div class="max-w-4xl px-4 sm:px-6 lg:px-8 mx-auto text-center">
            <h1
                class="text-3xl sm:text-4xl font-light text-gray-800 dark:text-gray-200 mb-2 transition-all duration-300">
                Bienvenue chez <span class="text-[#ff0] ">Vinify</span>
            </h1>
            <p class="mt-3 text-gray-600 dark:text-neutral-400">
                Scanner intelligent de plagiat
            </p>
        </div>
        <!-- End Title -->

        <ul class="mt-16 space-y-5" id="viewBox">


        </ul>
    </div>

    <div id="loading" style="display: none;" class="text-center mt-3">
        <svg class="animate-spin h-6 w-6 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>
        <p class="text-white mt-2">Analyse en cours...</p>
    </div>

    <!-- Textarea -->
    <div
        class="max-w-4xl mx-auto sticky bottom-0 z-10 p-4 sm:py-6 bg-white/70 dark:bg-neutral-900/70 backdrop-blur-md border-t border-gray-200 dark:border-neutral-700">
        <!-- Sidebar Toggle (mobile) -->
        <div class="lg:hidden flex justify-end mb-2 sm:mb-4">
            <button type="button"
                class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700"
                aria-label="Ouvrir la navigation" data-hs-overlay="#hs-application-sidebar">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" x2="21" y1="6" y2="6" />
                    <line x1="3" x2="21" y1="12" y2="12" />
                    <line x1="3" x2="21" y1="18" y2="18" />
                </svg>
                <span>Menu</span>
            </button>
        </div>

        <!-- Zone de saisie -->
        <div class="relative">
            <textarea id="textArea"
                class="block w-full h-32 max-h-60 resize-none overflow-auto p-4 pb-16 rounded-lg border border-gray-200 bg-gray-100 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                placeholder="Saisissez le texte ici ou déposez un document pour vérifier s'il y a le plagiat..."></textarea>

            <!-- Barre d’outils -->
            <div class="absolute bottom-px inset-x-px p-2 bg-gray-100 dark:bg-neutral-800 rounded-b-lg">
                <div class="flex justify-between items-center">
                    <!-- Partie gauche -->
                    <div class="flex items-center gap-2">
                        <!-- Spinner (chargement) -->
                        <div id="loadinginput" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </div>

                        <!-- Upload fichier -->
                        <input type="file" id="fileInput" accept=".txt,.pdf,.doc,.docx" class="hidden">
                        <label for="fileInput"
                            class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-white dark:hover:bg-neutral-700"
                            title="Importer un fichier">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48" />
                            </svg>
                        </label>
                    </div>

                    <!-- Partie droite (bouton envoyer) -->
                    <div>
                        <button type="button" id="analyzeBtn"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-yellow-300 text-gray-900 hover:bg-yellow-400 focus:outline-none transition"
                            title="Analyser">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855l-.452.18a.5.5 0 0 0-.082.887l.41.26 4.995 3.178 3.178 4.995.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <footer class="relative overflow-hidden bg-neutral-900">
        <svg class="absolute -bottom-20 start-1/2 w-[1900px] transform -translate-x-1/2" width="2745" height="488"
            viewBox="0 0 2745 488" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M0.5 330.864C232.505 403.801 853.749 527.683 1482.69 439.719C2111.63 351.756 2585.54 434.588 2743.87 487"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 308.873C232.505 381.81 853.749 505.692 1482.69 417.728C2111.63 329.765 2585.54 412.597 2743.87 465.009"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 286.882C232.505 359.819 853.749 483.701 1482.69 395.738C2111.63 307.774 2585.54 390.606 2743.87 443.018"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 264.891C232.505 337.828 853.749 461.71 1482.69 373.747C2111.63 285.783 2585.54 368.615 2743.87 421.027"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 242.9C232.505 315.837 853.749 439.719 1482.69 351.756C2111.63 263.792 2585.54 346.624 2743.87 399.036"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 220.909C232.505 293.846 853.749 417.728 1482.69 329.765C2111.63 241.801 2585.54 324.633 2743.87 377.045"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 198.918C232.505 271.855 853.749 395.737 1482.69 307.774C2111.63 219.81 2585.54 302.642 2743.87 355.054"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 176.927C232.505 249.864 853.749 373.746 1482.69 285.783C2111.63 197.819 2585.54 280.651 2743.87 333.063"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 154.937C232.505 227.873 853.749 351.756 1482.69 263.792C2111.63 175.828 2585.54 258.661 2743.87 311.072"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 132.946C232.505 205.882 853.749 329.765 1482.69 241.801C2111.63 153.837 2585.54 236.67 2743.87 289.082"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 110.955C232.505 183.891 853.749 307.774 1482.69 219.81C2111.63 131.846 2585.54 214.679 2743.87 267.091"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 88.9639C232.505 161.901 853.749 285.783 1482.69 197.819C2111.63 109.855 2585.54 192.688 2743.87 245.1"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 66.9729C232.505 139.91 853.749 263.792 1482.69 175.828C2111.63 87.8643 2585.54 170.697 2743.87 223.109"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 44.9819C232.505 117.919 853.749 241.801 1482.69 153.837C2111.63 65.8733 2585.54 148.706 2743.87 201.118"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 22.991C232.505 95.9276 853.749 219.81 1482.69 131.846C2111.63 43.8824 2585.54 126.715 2743.87 179.127"
                class="stroke-neutral-700/50" stroke="currentColor" />
            <path
                d="M0.5 1C232.505 73.9367 853.749 197.819 1482.69 109.855C2111.63 21.8914 2585.54 104.724 2743.87 157.136"
                class="stroke-neutral-700/50" stroke="currentColor" />
        </svg>

        <div class="relative z-10">
            <div class="w-full max-w-5xl px-4 xl:px-0 py-10 lg:pt-16 mx-auto">
                <div class="inline-flex items-center">
                    <a class="flex-none rounded-md text-xl inline-block text-[#ff0] font-semibold focus:outline-hidden focus:opacity-80"
                        href="#" aria-label="Preline">
                        Vinify
                    </a>

                    <div class="border-s border-neutral-700 ps-5 ms-5">
                        <p class="text-sm text-neutral-400">
                            © 2025 Vinify Labs.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            let file = event.target.files[0];
            const textArea = document.getElementById("textArea");
            const submitButton = document.getElementById("analyzeBtn");
            const loading = document.getElementById("loadinginput");

            // Ajuster la hauteur du textarea
            textArea.addEventListener("input", function() {
                this.style.height = "auto";
                this.style.height = (this.scrollHeight) + "px";

                // Activer/désactiver le bouton
                submitButton.disabled = this.value.trim() === "";
            });

            if (file) {
                let formData = new FormData();
                formData.append('text', file);

                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Afficher le spinner
                loading.classList.remove("hidden");
                submitButton.disabled = true; // Désactiver le bouton pendant l'upload

                fetch('/upload-text', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.info(data)
                        if (data.error) {
                            Swal.fire("Erreur", data.error, "error");
                        } else {
                            const extrait = data.text ? data.text.substring(0, 300) + (data.text.length > 300 ?
                                "..." : "") : "";
                            const fileUrl = data.file_url
                            const extension = fileUrl.split('.').pop().toLowerCase();
                            window.lastUploadedFileUrl = fileUrl;
                            window.lastUploadedFileExtension = extension;
                            window.documentId = data.document_id;
                            textArea.value = data.text;
                            textArea.dispatchEvent(new Event(
                                'input')); // Déclencher l'event input pour ajuster la hauteur
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', JSON.stringify(error));
                        Swal.fire("Erreur", "Une erreur s'est produite lors du téléchargement.", "error");
                    })
                    .finally(() => {
                        loading.classList.add("hidden");
                        submitButton.disabled = textArea.value.trim() ===
                            ""; // Réactiver si le texte est présent
                    });
            }
        });

        document.addEventListener("click", function(e) {
            if (e.target && e.target.classList.contains("voir-plus-btn")) {
                const fileUrl = e.target.getAttribute("data-file");
                const fileExtension = e.target.getAttribute("data-file-extension");
                if (fileExtension === 'pdf') {
                    Swal.fire({
                        title: "Aperçu du fichier",
                        html: `<iframe src="${fileUrl}" style="width:100%;height:500px;border:none;"></iframe>`,
                        width: 800,
                        showCloseButton: true,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        title: "Télécharger le fichier",
                        html: `<a href="${fileUrl}" class="btn btn-primary" download>Télécharger le fichier</a>`,
                        width: 800,
                        showCloseButton: true,
                        showConfirmButton: false
                    });
                }
            }
        });

        document.getElementById("analyzeBtn").addEventListener("click", function() {
            let textArea = document.getElementById("textArea");
            let text = textArea.value.trim();
            let resultDiv = document.getElementById("result");
            let answerDiv = document.getElementById("answer");
            let viewBox = document.getElementById("viewBox");
            let loader = document.getElementById("loading"); // Récupération du loader
            const extrait = text ? text.substring(0, 500) + (text.length > 500 ? "..." : "") : "";
            const fileUrl = window.lastUploadedFileUrl || "";
            const extension = window.lastUploadedFileExtension || "";
            console.log('fileUrl : ', fileUrl);
            console.log('extension : ', extension);
            if (!text) {
                alert("Veuillez entrer du texte avant l'analyse.");
                return;
            }

            // Afficher le texte analysé
            viewBox.innerHTML += `
                    <li class="max-w-4xl py-2 px-4 sm:px-6 lg:px-8 mx-auto flex gap-x-2 sm:gap-x-4">
                        <span class="shrink-0 inline-flex items-center justify-center size-9.5 rounded-full bg-gray-600">
                            <span class="text-sm font-medium text-white">AZ</span>
                        </span>
                        <div class="max-w-4xl mx-auto sticky bottom-0 z-10 p-4 sm:py-5 bg-white/10 dark:bg-neutral-800/50 backdrop-blur-lg border-t border-gray-200 dark:border-neutral-700 shadow-md rounded-xl">
                            <div class="max-w-2xl flex gap-x-2 sm:gap-x-4">
                                <div id="answer" class="grow mt-2 space-y-3">
                                    <p class="text-white  text-sm">${extrait}</p>
                                    <div class="grow">
                                        <button type="button" class="voir-plus-btn mt-2 py-1 px-3 rounded bg-yellow-300 text-gray-900" data-file="${fileUrl}" data-file-extension="${extension}">Voir le document en entier</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
            `;
            let statusMessageDiv = document.createElement('div');
            statusMessageDiv.id = 'analysis-status-message'; // Give it an ID to update later
            statusMessageDiv.className = 'max-w-4xl px-4 sm:px-6 lg:px-8 mx-auto mt-4';
            statusMessageDiv.innerHTML = `
                <p class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-blue-500 dark:text-neutral-400 dark:bg-neutral-800">
                    Contenu soumis avec succès. Veuillez patienter pendant l'analyse...
                </p>
            `;
            viewBox.appendChild(statusMessageDiv);
            textArea.value = "";
            textArea.style.height = "auto";

            // Afficher le loader et désactiver le bouton
            loader.style.display = "block";
            document.getElementById("analyzeBtn").disabled = true;

            fetch("/plagiarism-check", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            "content")
                    },
                    body: JSON.stringify({
                        text: text,
                        documentId: window.documentId,
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        // If response is not 2xx, throw an error
                        return response.json().then(err => {
                            throw new Error(err.message || 'Server error');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Job dispatch response:', data);

                    document.getElementById("viewBox").innerHTML += `
                        <div class="max-w-4xl px-4 sm:px-6 lg:px-8 mx-auto mt-4">
                            <p class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-blue-500 dark:text-neutral-400 dark:bg-neutral-800">
                                ${data.message}
                            </p>
                        </div>
                    `;
                    if (data.analysis_id) {
                        listenForAnalysisResults(data.analysis_id, statusMessageDiv, viewBox, loader);
                    } else {
                        alert('Erreur: L\'ID d\'analyse n\'a pas été renvoyé.');
                        resetUI(loader);
                    }
                })
                .catch(error => {
                    console.error("Erreur:", error);
                    viewBox.innerHTML +=
                        `
                        <li class="max-w-4xl py-2 px-4 sm:px-6 lg:px-8 mx-auto flex gap-x-2 sm:gap-x-4">
                            <span class="shrink-0 inline-flex items-center justify-center size-9.5 rounded-full bg-gray-800">
                                    <span class="text-sm font-medium text-white">JC</span>
                                </span>
                            <div class="grow max-w-[90%] md:max-w-2xl w-full space-y-3">
                                <!-- End Card -->

                                <div id="result"
                                    class="mt-3 flex-none min-w-full bg-gray-800 font-mono text-sm p-5 rounded-lg dark:bg-neutral-800 dark:text-neutral-200">
                                    <strong>Erreur :</strong> Impossible d'analyser le texte.${error}
                                </div>

                                <!-- Button Group -->
                                <div>
                                    <div class="sm:flex sm:justify-between">
                                        <div>
                                            <div
                                                class="inline-flex border border-gray-200 rounded-full p-0.5 dark:border-neutral-700">
                                                <button type="button"
                                                    class="inline-flex shrink-0 justify-center items-center size-8 rounded-full text-gray-500 hover:bg-blue-100 hover:text-blue-800 focus:z-10 focus:outline-hidden focus:bg-blue-100 focus:text-blue-800 dark:text-neutral-500 dark:hover:bg-blue-900 dark:hover:text-blue-200 dark:focus:bg-blue-900 dark:focus:text-blue-200">
                                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M7 10v12" />
                                                        <path
                                                            d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z" />
                                                    </svg>
                                                </button>
                                                <button type="button"
                                                    class="inline-flex shrink-0 justify-center items-center size-8 rounded-full text-gray-500 hover:bg-blue-100 hover:text-blue-800 focus:z-10 focus:outline-hidden focus:bg-blue-100 focus:text-blue-800 dark:text-neutral-500 dark:hover:bg-blue-900 dark:hover:text-blue-200 dark:focus:bg-blue-900 dark:focus:text-blue-200">
                                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M17 14V2" />
                                                        <path
                                                            d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22h0a3.13 3.13 0 0 1-3-3.88Z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <button type="button"
                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-gray-500 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 14V2" />
                                                    <path
                                                        d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22h0a3.13 3.13 0 0 1-3-3.88Z" />
                                                </svg>
                                                Copy
                                            </button>
                                            <button type="button"
                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-gray-500 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="18" cy="5" r="3" />
                                                    <circle cx="6" cy="12" r="3" />
                                                    <circle cx="18" cy="19" r="3" />
                                                    <line x1="8.59" x2="15.42" y1="13.51" y2="17.49" />
                                                    <line x1="15.41" x2="8.59" y1="6.51" y2="10.49" />
                                                </svg>
                                                Share
                                            </button>
                                        </div>

                                        <div class="mt-1 sm:mt-0">
                                            <button type="button"
                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-gray-500 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8" />
                                                    <path d="M21 3v5h-5" />
                                                </svg>
                                                New answer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Button Group -->
                            </div>
                        </li>
                        <p class="text-red-400"></p>

                        `;
                })
                .finally(() => {
                    // Cacher le loader et réactiver le bouton après la requête
                    // loader.style.display = "none";
                    document.getElementById("analyzeBtn").disabled = false;
                });
        });

        function listenForAnalysisResults(analysisId, statusMessageDiv, resultsContainerDiv, loaderElement) {
            console.log('Listening for analysis on channel:', `plagiarism-analysis.${analysisId}`);
            console.log('Echo:', window.Echo);
            if (window.Echo) {
                console.log('Leaving the channel: ', `plagiarism-analysis.${analysisId}`)
                window.Echo.leave(`plagiarism-analysis.${analysisId}`);
            } else {
                console.warn('Laravel Echo n\'est pas initialisé. Le broadcasting ne fonctionnera pas.');
                return;
            }

            // Écoute le canal spécifique à cette analyse
            window.Echo.channel(`plagiarism-analysis.${analysisId}`)
                .listen('.analysis-completed', (e) => {
                    console.log('Analysis completed event received:', e);
                    let statusText = '';
                    let statusColorClass = '';
                    const textAnalysisId = e.textAnalysisId;
                    const status = e.status;

                    if (status === 'completed') {
                        statusText = 'Analyse terminée avec succès ✅';
                        statusColorClass = 'text-green-500';
                    } else {
                        statusText = `Analyse échouée ❌`;
                        statusColorClass = 'text-red-500';
                    }

                    statusMessageDiv.innerHTML = `
                            <p class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent ${statusColorClass} dark:text-neutral-400 dark:bg-neutral-800">
                                ${statusText}
                            </p>
                        `;

                    fetch(`/api/analysis/${textAnalysisId}/status`, {
                            method: "GET",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.message ||
                                        'Erreur lors de la récupération des détails de l\'analyse.');
                                });
                            }
                            return response.json();
                        })
                        .then(analysisData => {
                            window.similaritiesList = analysisData.similarities || [];
                            console.log('Full analysis data retrieved from API:', analysisData);

                            displayAnalysisResults(textAnalysisId, analysisData, resultsContainerDiv);

                            // Déclencher la notification pour l'utilisateur
                            let notificationTitle = "Analyse de Plagiat Terminée !";
                            let notificationMessage =
                                `L'analyse de votre document (ID: ${analysisData.id}) est ${analysisData.status === 'completed' ? 'terminée avec succès.' : 'échouée.'}`;
                            let notificationUrl =
                                `/analyses/${analysisData.id}`;

                            showBrowserNotification(notificationTitle, notificationMessage, notificationUrl);
                        })
                        .catch(error => {
                            console.error("Erreur lors de la récupération des détails de l'analyse via l'API:",
                                error);
                            // Mettre à jour l'UI avec un message d'erreur si la récupération API échoue
                            resultsContainerDiv.innerHTML += `
                                <div class="max-w-4xl px-4 sm:px-6 lg:px-8 mx-auto mt-4 text-red-500">
                                    <p>Erreur critique: Impossible de récupérer les résultats détaillés de l'analyse: ${error.message}</p>
                                </div>
                            `;
                            showBrowserNotification("Erreur d'analyse",
                                `Impossible de récupérer les résultats de l'analyse (ID: ${textAnalysisId}).`
                            );
                        })
                        .finally(() => {
                            resetUI(loaderElement); // Masquer le loader et réactiver le bouton
                            window.Echo.leave(
                                `plagiarism-analysis.${textAnalysisId}`
                            );
                        });
                })
                .error((error) => {
                    console.error('WebSocket Error on channel ' + `plagiarism-analysis.${analysisId}` + ':', error);
                    statusMessageDiv.innerHTML = `
                        <p class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-red-500 dark:text-neutral-400 dark:bg-neutral-800">
                            Erreur de connexion en temps réel. Veuillez recharger la page.
                        </p>
                    `;
                    resetUI(loaderElement); // Assurez-vous de réinitialiser l'UI même en cas d'erreur WebSocket
                });
        }

        function displayAnalysisResults(textAnalysisId, data, resultsContainerDiv) {
            const similarities = data.similarities;
            const highlightedText = data.highlighted_text || "Aucun texte mis en surbrillance.";
            const isAiGenerated = data.is_ai_generated;
            const excerpts = data.excerpted_text ?? [];
            const plagiarismPercentage = data.plagiarism_percentage ?? 0;

            if (excerpts && excerpts.length > 0) {
                excerpts.forEach(ex => {
                    resultsContainerDiv.innerHTML += `
                        <div class="max-w-4xl px-4 sm:px-6 lg:px-8 mx-auto mt-4">
                            <p class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-blue-500 dark:text-neutral-400 dark:bg-neutral-800">
                                ${ex.highlighted}
                            </p>
                        </div>
                    `;
                });
                resultsContainerDiv.innerHTML += `
                    <br/><hr/>
                    <div class="max-w-4xl px-4 sm:px-6 lg:px-8 mx-auto mt-4">
                        <p class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-blue-500 dark:text-neutral-400 dark:bg-neutral-800">
                            <strong>Pourcentage de plagiat : </strong>${plagiarismPercentage.toFixed(1)}%
                        </p>
                    </div>
                    <div class="max-w-4xl py-2 px-4 sm:px-6 lg:px-8 gap-x-2 sm:gap-x-4 mx-auto dark:bg-neutral-800 ${isAiGenerated ? 'text-orange-500' : 'text-green-400'} text-gray-500 p-3 rounded">
                        <p class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-blue-500 dark:text-neutral-400 dark:bg-neutral-800">
                            <strong>Généré par IA : </strong>${isAiGenerated ? 'Oui' : 'Non'}
                        </p>
                    </div>
                `;
                resultsContainerDiv.innerHTML += `
                    <div class="max-w-4xl py-2 px-4 sm:px-6 lg:px-8 mx-auto flex flex-column gap-x-2 sm:gap-x-4">
                        <a href="/analyses/${textAnalysisId}" type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-gray-500 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M4.998 7.78C6.729 6.345 9.198 5 12 5c2.802 0 5.27 1.345 7.002 2.78a12.713 12.713 0 0 1 2.096 2.183c.253.344.465.682.618.997.14.286.284.658.284 1.04s-.145.754-.284 1.04a6.6 6.6 0 0 1-.618.997 12.712 12.712 0 0 1-2.096 2.183C17.271 17.655 14.802 19 12 19c-2.802 0-5.27-1.345-7.002-2.78a12.712 12.712 0 0 1-2.096-2.183 6.6 6.6 0 0 1-.618-.997C2.144 12.754 2 12.382 2 12s.145-.754.284-1.04c.153-.315.365-.653.618-.997A12.714 12.714 0 0 1 4.998 7.78ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>
                            </svg>
                            Voir les détails de l'analyse du document
                        </a>
                    </div>
                `;
            } else {
                resultsContainerDiv.innerHTML += `
                    <div class="max-w-4xl px-4 sm:px-6 lg:px-8 mx-auto flex gap-x-2 sm:gap-x-4">
                        <p class="py-2 px-3 inline-flex items-center gap-x-2 text-sm rounded-full border border-transparent text-green-500 dark:text-neutral-400 dark:bg-neutral-800">
                            Rien à signaler dans votre document. Tout est en ordre ✅.
                        </p>
                    </div>
                `;
            }
        }

        function resetUI(loaderElement) {
            loaderElement.style.display = "none";
            document.getElementById("analyzeBtn").disabled = false;
        }

        function showBrowserNotification(title, message, url = null) {
            if (Notification.permission === "default") {
                Notification.requestPermission();
            }

            if (Notification.permission === "granted") {
                const notification = new Notification(title, {
                    body: message,
                    icon: '/public/vinify.png'
                });

                if (url) {
                    notification.onclick = function(event) {
                        event.preventDefault();
                        window.open(url, '_blank');
                        notification.close();
                    };
                }
            } else {
                alert(`${title}: ${message}`);
            }
        }
    </script>
    <script>
        document.addEventListener("click", function(e) {
            if (e.target && e.target.classList.contains("plagiarized")) {
                const dataId = e.target.getAttribute("data-id");
                const similarData = window.similaritiesList?.find(item => item.id == dataId);

                if (similarData) {
                    Swal.fire({
                        title: "Détail du plagiat",
                        html: `
                            <div class="p-1 rounded-lg text-start">
                                <p class="mb-2"><strong>Phrase :</strong> ${similarData.plagiarized_text}</p>
                                <p class="mb-2"><strong>Similarité :</strong> ${similarData.similarity_percentage}%</p>
                                <p class="mb-2 text-start" title="Cliquez pour suivre le lien de la source..."><strong>Source :</strong> <a href="${similarData.link}" target="_blank" class="underline">${similarData.title}</a></p>
                            </div>
                        `,
                        icon: "info",
                        customClass: {
                            popup: 'bg-gray-200 dark:bg-gray-900 text-start text-black dark:text-gray-50 rounded-lg shadow-lg',
                            confirmButton: 'dark:bg-[#ff0] dark:text-black font-bold py-2 px-4 rounded',
                        },
                    });
                }
            }
        });
    </script>
</x-app-layout>
