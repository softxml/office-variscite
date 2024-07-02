jQuery(document).ready(function($) {
    // Handle the search input
    $('#srp-search').on('input', function() {
        var query = $(this).val();
        console.log('Search query:', query); // Debugging line

        if (query.length < 3) {
            $('#srp-search-results').empty();
            return;
        }

        // Show loading indicator
        $('#srp-search-results').html('<li class="loading">Loading...</li>');
        console.log('Sending AJAX request to:', srp_ajax_object.ajax_url); // Debugging line

        $.ajax({
            url: srp_ajax_object.ajax_url,
            method: 'POST',
            data: {
                action: 'srp_search_specs_products',
                query: query
            },
            success: function(response) {
                console.log('AJAX response:', response); // Debugging line

                if (response.success) {
                    var results = response.data;
                    $('#srp-search-results').empty();
                    results.forEach(function(product) {
                        if (!isProductAdded(product.id)) {
                            $('#srp-search-results').append(
                                '<li data-product-id="' + product.id + '">' +
                                product.title +
                                '<button class="srp-add-product">Add</button>' +
                                '</li>'
                            );
                        }
                    });
                } else {
                    $('#srp-search-results').html('<li>No results found.</li>');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', error); // Debugging line
                console.log('XHR:', xhr);
                console.log('Status:', status);
                console.log('Response Text:', xhr.responseText);
                $('#srp-search-results').html('<li>Error loading results.</li>');
            }
        });
    });

    // Handle adding a product
    $('#srp-search-results').on('click', '.srp-add-product', function() {
        var productId = $(this).parent().data('product-id');
        var productTitle = $(this).parent().text().replace('Add', '');

        if (!isProductAdded(productId)) {
            $('#srp-related-products-list ul').append(
                '<li data-product-id="' + productId + '">' +
                productTitle +
                '<button class="srp-remove-product">Remove</button>' +
                '</li>'
            );

            // Remove product from search results
            $(this).parent().remove();
            updateRelatedProducts();
        } else {
            alert('This product is already added.');
        }
    });

    // Handle removing a product
    $('#srp-related-products-list').on('click', '.srp-remove-product', function() {
        $(this).parent().remove();
        updateRelatedProducts();
    });

    // Update the hidden input field with the list of related product IDs
    function updateRelatedProducts() {
        var productIds = [];
        $('#srp-related-products-list ul li').each(function() {
            productIds.push($(this).data('product-id'));
        });
        $('#srp-related-products').val(productIds.join(','));
        console.log('Updated related products:', productIds); // Debugging line
    }

    // Check if a product is already added
    function isProductAdded(productId) {
        var isAdded = false;
        $('#srp-related-products-list ul li').each(function() {
            if ($(this).data('product-id') == productId) {
                isAdded = true;
                return false; // Exit each loop
            }
        });
        return isAdded;
    }
});
