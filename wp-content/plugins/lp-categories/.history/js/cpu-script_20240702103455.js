jQuery(document).ready(function($) {
    // Handle the search input
    $('#cpu-search').on('input', function() {
        var query = $(this).val();
        $('.cpu-clear-search').toggle(query.length > 0); // Toggle the clear icon
        console.log('Search query:', query); // Debugging line

        if (query.length < 3) {
            $('#cpu-search-results').empty();
            return;
        }

        // Show loading indicator
        $('#cpu-search-results').html('<li class="loading">Loading...</li>');
        console.log('Sending AJAX request to:', cpu_ajax_object.ajax_url); // Debugging line

        $.ajax({
            url: cpu_ajax_object.ajax_url,
            method: 'POST',
            data: {
                action: 'cpu_search_names',
                query: query
            },
            success: function(response) {
                console.log('AJAX response:', response); // Debugging line

                if (response.success) {
                    var results = response.data;
                    $('#cpu-search-results').empty();
                    results.forEach(function(cpu) {
                        $('#cpu-search-results').append(
                            '<li data-cpu-id="' + cpu.id + '">' +
                            '<span class="cpu-name">' + cpu.name + '</span>' +
                            '<a href="' + cpu.link + '" target="_blank">View Products</a>' +
                            '<button class="cpu-add">Add</button>' +
                            '</li>'
                        );
                    });
                } else {
                    $('#cpu-search-results').html('<li>No results found.</li>');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', error); // Debugging line
                console.log('XHR:', xhr);
                console.log('Status:', status);
                console.log('Response Text:', xhr.responseText);
                $('#cpu-search-results').html('<li>Error loading results.</li>');
            }
        });
    });

    // Clear search input
    $('.cpu-clear-search').on('click', function() {
        $('#cpu-search').val('').trigger('input');
    });

    // Handle adding a CPU
    $('#cpu-search-results').on('click', '.cpu-add', function() {
        var cpuId = $(this).parent().data('cpu-id');
        var cpuName = $(this).siblings('.cpu-name').text();

        if (!isCpuAdded(cpuId)) {
            $('#cpu-list').append(
                '<li data-cpu-id="' + cpuId + '">' +
                cpuName +
                '<button class="cpu-remove">Remove</button>' +
                '</li>'
            );

            // Remove CPU from search results
            $(this).parent().remove();
            updateCpuList();
        } else {
            alert('This CPU is already added.');
        }
    });

    // Handle removing a CPU
    $('#cpu-list').on('click', '.cpu-remove', function() {
        $(this).parent().remove();
        updateCpuList();
    });

    // Update the hidden input field with the list of CPU IDs
    function updateCpuList() {
        var cpuIds = [];
        $('#cpu-list li').each(function() {
            cpuIds.push($(this).data('cpu-id'));
        });
        $('#cpu-related-products').val(cpuIds.join(','));
        console.log('Updated CPU list:', cpuIds); // Debugging line
    }

    // Check if a CPU is already added
    function isCpuAdded(cpuId) {
        var isAdded = false;
        $('#cpu-list li').each(function() {
            if ($(this).data('cpu-id') == cpuId) {
                isAdded = true;
                return false; // Exit each loop
            }
        });
        return isAdded;
    }
});
