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
    <div class="max-w-4xl py-2 px-4 sm:px-6 lg:px-8 mx-auto flex gap-x-2 sm:gap-x-4">
        <pre class="bg-gray-800 text-white p-3 rounded overflow-auto whitespace-pre-wrap">{!! $textAnalysis->highlighted_text !!}</pre>
    </div>

    <script>
        window.similaritiesList = @json($similaritiesList);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('pre') || document; // ou un div parent
            console.log(container);
            container.addEventListener('click', e => {
                const span = e.target.closest('span.plagiarized');
                console.log(span);
                if (!span) return;

                const id = span.dataset.id;
                console.log(id)
                const data = window.similaritiesList?.find(item => item.id == id);
                console.log(data);
                if (!data) return;

                showModal(data);
            });
        });

        function showModal(data) {
            Swal.fire({
                title: "Détail du plagiat",
                html: `
                    <p><strong>Phrase :</strong> ${data.plagiarized_text}</p>
                    <p><strong>Similarité :</strong> ${data.similarity_percentage}%</p>
                    <p><strong>Source :</strong> <a href="${data.link}" target="_blank">${data.title}</a></p>
                `,
                icon: "info"
            });
        }
    </script>

</x-app-layout>
