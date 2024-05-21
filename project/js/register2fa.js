const qrCodeContainer = document.getElementById('qr-code-container');

fetch('php/register2fa.php', {
  method: 'GET'
})
.then(response => response.json())  
.then(data => {
  const qrCodeImage = document.createElement('img');
  qrCodeImage.src = data.qrCodeData;  
  qrCodeContainer.appendChild(qrCodeImage);
})
.catch(error => {
  console.error('Error fetching QR code data:', error);
});