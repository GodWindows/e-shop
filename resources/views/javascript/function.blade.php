<script>

    function getCartFromlocalStorage() {
        const savedCart = localStorage.getItem('cart');
        return savedCart ? new Map(JSON.parse(savedCart)) : new Map([]);
    }

    function refreshCartCount() {
        var cart = getCartFromlocalStorage();
        var itemsCount = 0;
        for (var [key, value] of cart) {
            itemsCount++;
        }
        var itemsCountTag = document.querySelector("#cartItemsCount");
        itemsCountTag.innerHTML = itemsCount;
    }
    function addToCart(productId, productAmount=1) {
        //on récupère le panier
        var cart = getCartFromlocalStorage();
        console.log([...cart]);
        var productCount = cart.get(productId) ?? 0;
        var newAmount = productCount + productAmount;
        cart.set(productId, newAmount);
        //on convertit la Map 'cart' en string  pour la stocker
        localStorage.setItem("cart", JSON.stringify(Array.from(cart.entries())));
        console.log("addToCart called");
        localStorage.setItem("monChat", "Tom");
        console.log(cart);
        
        refreshCartCount();
        
    }
</script>