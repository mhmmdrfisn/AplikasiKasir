// Fungsi untuk melakukan pencarian
function search() {
    // Ambil nilai input pencarian
    var input = document.getElementById("searchInput").value.toUpperCase();

    // Ambil tabel dan baris data
    var table = document.querySelector(".table");
    var rows = table.getElementsByTagName("tr");

    // Inisialisasi variabel untuk menentukan apakah ada hasil pencarian atau tidak
    var foundItems = false;

    // Iterasi melalui setiap baris data dan setiap kolom untuk melakukan pencarian
    for (var i = 1; i < rows.length; i++) {
        var foundInRow = false; // Inisialisasi variabel untuk menentukan apakah ada hasil pencarian dalam baris tertentu
        for (var j = 0; j < rows[i].cells.length; j++) {
            var cell = rows[i].cells[j];
            if (cell) {
                var textValue = cell.textContent || cell.innerText;
                if (textValue.toUpperCase().indexOf(input) > -1) {
                    foundInRow = true;
                    foundItems = true;
                }
            }
        }
        // Tampilkan atau sembunyikan baris berdasarkan hasil pencarian pada baris tersebut
        rows[i].style.display = foundInRow ? "" : "none";
    }

    // Tampilkan atau sembunyikan pesan jika tidak ada hasil pencarian
    var noResultMessage = document.getElementById("noResultMessage");
    noResultMessage.style.display = foundItems ? "none" : "block";
}

// Tambahkan event listener untuk pemanggilan fungsi pencarian saat nilai input berubah
document.getElementById("searchInput").addEventListener("input", search);
