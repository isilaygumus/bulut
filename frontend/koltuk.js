const seatGrid = document.getElementById('seat-grid');
const onayla = document.getElementById('confirm-selection');

const fiyat = 50; 

const sira = 10;
const cols = 12;
const sira_ad = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
const dolu = [1, 13, 19, 24, 30]; 


sira_ad.forEach((label, rowIndex) => {

    for (let col = 1; col <= cols; col++) {
        const seatNumber = rowIndex * cols + col;
        const seat = document.createElement('div');
        seat.classList.add('seat');
        seat.textContent = `${label}${col}`;

        if (dolu.includes(seatNumber)) {
            seat.classList.add('occupied');
        } else {
            seat.addEventListener('click', () => {
                seat.classList.toggle('selected');
            });
        }

        seatGrid.appendChild(seat);
    }
});

onayla.addEventListener('click', () => {
    const secilen = document.querySelectorAll('.seat.selected');
    const seatNumbers = Array.from(secilen).map(seat => seat.textContent);

    if (seatNumbers.length > 0) {
        localStorage.setItem('secilen', JSON.stringify(seatNumbers));
        alert(`Seçilen koltuklar: ${seatNumbers.join(', ')}\nToplam Ücret: ${seatNumbers.length * fiyat} TL`);
        updateSummary();
    } else {
        updateSummary();
    }
});

function updateSummary() {
    const secilen = document.querySelectorAll('.seat.selected');
    const selectedCount = secilen.length;
    const totalPrice = selectedCount * fiyat;

    document.getElementById('selected-count').textContent = selectedCount;
    document.getElementById('total-price').textContent = totalPrice;
}
