<script>
    $(document).ready(function() {
        const $searchInput = $('#search-input');
        const $searchResults = $('#search-results');
        const $searchIcon = $('#search-icon');

        function performSearch(input, resultsContainer) {
            let query = input.val();
            if (query.length > 2) {
                $.ajax({
                    url: "{{ route('search.products') }}",
                    method: 'GET',
                    data: { query: query },
                    success: function(data) {
                        resultsContainer.html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching search results:', error);
                        resultsContainer.html('<div class="p-2">An error occurred</div>');
                    }
                });
            } else {
                resultsContainer.html('');
            }
        }

        $searchInput.on('keyup', function() {
            performSearch($searchInput, $searchResults);
        });
        $searchIcon.on('click', function() {
            performSearch($searchInput, $searchResults);
        });

        const $searchInputSmall = $('#search-input-small');
        const $searchResultsSmall = $('#search-results-small');
        const $searchIconSmall = $('#search-icon-small');

        $searchInputSmall.on('keyup', function() {
            performSearch($searchInputSmall, $searchResultsSmall);
        });
        $searchIconSmall.on('click', function() {
            performSearch($searchInputSmall, $searchResultsSmall);
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#search-form, #search-form-small').length) {
                $searchResults.html('');
                $searchResultsSmall.html('');
            }
        });
    });
</script>