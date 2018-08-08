
$(document).ready(function(){

        $.ajax({
            type: "POST",
            url: js_urls.cart_summary_url,
            data: { }
        }).done(function( msg ) {
            if(msg.status == 'empty'){
                $('.cart-page h4').html("Your cart is empty.");
            }
            else if(msg.status == 'contains'){

                var total = msg.products.length;
                var html = '<table class="table cart-table"><thead><tr><th colspan="2">Product</th><th>Qty</th><th>Price</th></tr></thead><tbody>';
                var total_price = 0.0;
                for (var i=0; i < total; ++i){
                    var product = msg.products[i];
                    var product_price = (parseFloat(product.order_qty) * parseFloat(product.price)).toFixed(2);
                    var options = '';
                    if(product.options.length > 1){
                        options = " ( " + product.option.title + " ) ";
                    }

                    product.main_image = js_setting.site_url+"/"+js_setting.thumbs_dir+"/"+product.main_image;

                    var qty_html = product.order_qty ;

                    html += '<tr><td><span class="hidden-xs"><img src="'+product.main_image+'" class="cart-img" /><span/></td><td><a href="'+js_urls.product_url+'/'+product.product_id+'">'+product.title+options+'</a></td><td>'+qty_html+'</td><td><span class="hidden-xs">'+product.order_qty+'x'+product.price.toFixed(2)+'=</span>'+(product_price)+'</td>'+
                        '</tr>';
                    total_price = parseFloat(total_price) + parseFloat(product_price);
                }
                html += '<tr><td colspan="2"><a href="'+js_urls.cart_url+'" class="btn btn-default">Modify Order</a></td><td></td><td>Total: '+js_setting.currency_prefix+total_price+js_setting.currency_postfix+' </td></tr>';
                html += "</tbody></table>";
                $('.cart-page h4').replaceWith(html);


                $(".qty-plus").click(function(){
                    var ind = $(".qty-plus").index(this);
                    var order_qty = parseInt($(".order_qty").eq(ind).val());
                    if(isNaN(order_qty) || order_qty <= 0){
                        $(".order_qty").eq(ind).val(1);
                    }
                    else{
                        $(".order_qty").eq(ind).val(++order_qty);
                    }
                });
                $(".qty-minus").click(function(){
                    var ind = $(".qty-minus").index(this);
                    var order_qty = parseInt($(".order_qty").eq(ind).val());
                    if(isNaN(order_qty) || order_qty <= 1){
                        $(".order_qty").eq(ind).val(1);
                    }
                    else{
                        $(".order_qty").eq(ind).val(--order_qty);
                    }
                });
            }
            else{
                alert("There was an error in response from server. Try again by refreshing the page.");
            }
        });


});
