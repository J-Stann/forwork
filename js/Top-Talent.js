$(document).ready(function() {
    // Category suggestions
    $('#category-filter').on('input', function() {
        const query = $(this).val();
        if (query.length >= 2) {
            $.get('Top-Talent.php', { suggest_query: query }, function(data) {
                const suggestions = JSON.parse(data);
                const dropdown = $('#category-suggestions');
                dropdown.empty();
                
                if (suggestions.length > 0) {
                    suggestions.forEach(suggestion => {
                        dropdown.append(`<div class="suggestion-item p-2 hover:bg-gray-100 cursor-pointer">${suggestion}</div>`);
                    });
                    dropdown.removeClass('hidden');
                } else {
                    dropdown.addClass('hidden');
                }
            });
        } else {
            $('#category-suggestions').addClass('hidden');
        }
    });
    
    // Handle suggestion selection
    $(document).on('click', '.suggestion-item', function() {
        $('#category-filter').val($(this).text());
        $('#category-suggestions').addClass('hidden');
        $('#talentFilters').submit();
    });
    
    // View More button
    $('#view-more-btn').click(function() {
        const currentPage = parseInt($('#page-input').val());
        $('#page-input').val(currentPage + 1);
        $('#talentFilters').submit();
    });
    
    // Loading indicator for form submission
    $('#talentFilters').on('submit', function(e) {
        e.preventDefault();
        $('#loading-indicator').removeClass('hidden');
        $('#talent-grid').css('opacity', '0.5');
        
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                const newDoc = new DOMParser().parseFromString(response, 'text/html');
                const newContent = $(newDoc).find('#talent-grid').html();
                
                if ($('#page-input').val() > 1) {
                    $('#talent-grid').append(newContent);
                } else {
                    $('#talent-grid').html(newContent);
                }
                
                // Update view more button
                const viewMoreBtn = $(newDoc).find('#view-more-btn');
                if (viewMoreBtn.length) {
                    $('#view-more-btn').replaceWith(viewMoreBtn);
                } else {
                    $('#view-more-btn').remove();
                }
            },
            complete: function() {
                $('#loading-indicator').addClass('hidden');
                $('#talent-grid').css('opacity', '1');
            }
        });
    });
});