$(document).ready(function() {
    $(".slick-carousel").slick({
        dots: true,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
    });
});

function redirectToCategory() {
    var selectedCategory = document.getElementById("categoryDropdown").value;
    if (selectedCategory === "all") {
        window.location.href = "produtos.php";
    } else {
        window.location.href = "produtos.php?categoria=" + encodeURIComponent(selectedCategory);
    }
}

// Função para exibir o modal
function openSupportModal() {
    var modal = document.getElementById("supportModal");
    modal.style.display = "block";
}

// Função para fechar o modal
function closeSupportModal() {
    var modal = document.getElementById("supportModal");
    modal.style.display = "none";
}

// Event listener para abrir o modal quando o botão de suporte for clicado
document.getElementById("supportButton").addEventListener("click", openSupportModal);

// Event listener para fechar o modal quando o botão de fechar for clicado
document.getElementsByClassName("close-button")[0].addEventListener("click", closeSupportModal);

// Event listener para fechar o modal quando a área escura ao redor do modal for clicada
window.addEventListener("click", function (event) {
    var modal = document.getElementById("supportModal");
    if (event.target === modal) {
        closeSupportModal();
    }
});
