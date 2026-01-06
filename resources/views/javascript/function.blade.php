<script>

    refreshCartCount();

    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.style.display = 'block';
        setTimeout(() => t.style.display = 'none', 2000);
    }

    // Function to create the cookie 
    function saveCartInCookie(value, days) {
        let expires;
        name = 'cart';
        //on convertit la Map 'value' en string  pour la stocker
        var value = JSON.stringify(Array.from(value.entries()));

        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        else {
            expires = "";
        }

        document.cookie = escape(name) + "=" +
            escape(value) + expires + "; path=/";
    }

    // Function to create the cookie 
    function getCartFromCookie() {
        name = "cart";
        var escapeName = escape(name) + "=";
        const cookieValue = document.cookie
        .split("; ")
        .find((row) => row.startsWith(escapeName))
        ?.split("=")[1];
        var value = unescape(cookieValue);
        
        return (cookieValue) ? new Map(JSON.parse(value)) : new Map([]);
    }

    function refreshCartCount() {
        var cart = getCartFromCookie();
        var itemsCount = 0;
        for (var [key, value] of cart) {
            itemsCount++;
        }
        var itemsCountTag = document.querySelector("#cartItemsCount");
        itemsCountTag.innerHTML = itemsCount;
    }
    function addToCart(productId) {
        productAmount = Number(document.querySelector('#itemCount_'+productId).value);
        
        //on récupère le panier
        var cart = getCartFromCookie();
        console.log(cart);
        var productCount = Number(cart.get(productId)) || 0;
        console.log("productCount : "+productCount);
        var newAmount = productCount + productAmount;
        cart.set(productId, newAmount);
        saveCartInCookie(cart, 7);
        console.log("addToCart called");
        console.log(cart);
        showToast("Ajouté au panier!");
        refreshCartCount();
        
    }

function editAmountInCart(productId) {
    productAmount = Number(document.querySelector('#itemCount_'+productId).value);
     
    //on récupère le panier
    var cart = getCartFromCookie();
    console.log([...cart]);
    var productCount = Number(cart.get(productId)) || 0;
    var newAmount = productAmount;
    cart.set(productId, newAmount);
    saveCartInCookie(cart, 7);
    console.log("addToCart called");
    console.log(cart);
    if (newAmount <= 0) {
        deleteFromCart(productId);
    }
    refreshCartCount();
    showToast("Panier modifié");

    // Update the visual total if function exists
    if (typeof window.updateCartTotalDisplay === 'function') {
        window.updateCartTotalDisplay();
    }
    
}

function deleteFromCart(productId) {
     
    //on récupère le panier
    var cart = getCartFromCookie();

    cart.delete(productId);
    console.log([...cart]);
    saveCartInCookie(cart, 7);
    console.log("addToCart called");
    console.log(cart);
    
    refreshCartCount();
    showToast("Supprimé");

    // Update the visual total if function exists
    if (typeof window.updateCartTotalDisplay === 'function') {
        window.updateCartTotalDisplay();
    }

    // Remove the element from the DOM
    const row = document.getElementById('product-row-' + productId);
    if (row) {
        row.remove();
    }
    
    // Check if cart is empty after deletion
    if (cart.size === 0) {
        // Optionally show "Cart is empty" message or reload to show empty state
        window.location.reload();
    }
    
}
</script>