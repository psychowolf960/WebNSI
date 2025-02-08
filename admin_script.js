document.addEventListener("DOMContentLoaded", function () {
    console.log("Admin Panel JS chargé.");

    // 🔹 Confirmation avant suppression
    document.querySelectorAll(".delete-form").forEach((form) => {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Empêche l'envoi direct du formulaire
            let confirmDelete = confirm("Voulez-vous vraiment supprimer cet élément ?");
            if (confirmDelete) {
                this.submit(); // Soumet le formulaire si confirmé
            }
        });
    });

    // 🔹 Affichage des messages temporaires (succès/erreur)
    let alertBox = document.querySelector(".alert");
    if (alertBox) {
        alertBox.style.display = "block";
        setTimeout(() => {
            alertBox.style.opacity = "0";
            setTimeout(() => {
                alertBox.style.display = "none";
            }, 500);
        }, 3000); // Disparition après 3 secondes
    }
});
