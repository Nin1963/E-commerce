const stripe = Stripe(stripePublicKey);

const elements = stripe.elements();

const style = {
    base: {
    color: "#32325d",
    fontFamily: 'Arial, sans-serif',
    fontSmoothing: "antialiased",
    fontSize: "16px",
    "::placeholder": {
        color: "#32325d"
        }
    },
    invalid: {
    fontFamily: 'Arial, sans-serif',
    color: "#fa755a",
    iconColor: "#fa755a"
    }
} 

const card = elements.create("card", { style:style });

// Stripe injects an iframe into the DOM

card.mount("#card-element");

card.on("change", function (event) {
    // Disable the Pay button if there are no card details in the Element
    document.querySelector("button").disabled = event.empty;
    document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
});

const form = document.getElementById("payment-form");

form.addEventListener("submit", function(event) {
    event.preventDefault();
    
    // Complete payment when the submit button is clicked
    //payWithCard(stripe, card, data.clientSecret);
    
    stripe
        .confirmCardPayment(clientSecret, {
            payment_method: {
            card: card
            }
        })
        .then(function(result) {
            if (result.error) {
                // Show error to your customer
                console.log(result.error.message);
            } else {
                // The payment succeeded!
                window.location.href = redirectAfterSuccessUrl;
            }
        });
}); 