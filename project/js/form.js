document.getElementById('fitnessForm').addEventListener('submit', function(event) {
    var altura = document.getElementById('altura').value;
    var peso = document.getElementById('peso').value;
    var dias = document.getElementById('dias').value;

    if (altura <= 0 || peso <= 0 || dias <= 0 || dias > 7) {
        alert("Por favor, insira valores válidos.");
        event.preventDefault();
    }
});
