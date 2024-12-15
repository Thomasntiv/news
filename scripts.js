const cacheKey = "newsData"; // Όνομα για την αποθήκευση
const cacheTimeKey = "newsCacheTime"; // Χρόνος τελευταίας αποθήκευσης
const cacheDuration = 5 * 60 * 1000; // 5 λεπτά σε χιλιοστά του δευτερολέπτου

const now = Date.now();

// Ελέγχει αν υπάρχουν δεδομένα στην cache
const cachedData = localStorage.getItem(cacheKey);
const cachedTime = localStorage.getItem(cacheTimeKey);

if (cachedData && cachedTime && now - cachedTime < cacheDuration) {
    // Εμφάνιση δεδομένων από την cache
    document.getElementById("news-container").innerHTML = cachedData;
} else {
    // Ανάκτηση νέων δεδομένων από το API
    fetch('https://api.rss2json.com/v1/api.json?rss_url=https://www.example.com/rss')
        .then(response => response.json())
        .then(data => {
            const newsHTML = data.items.map(item => `
                <div>
                    <h3>${item.title}</h3>
                    <a href="${item.link}" target="_blank">Διαβάστε περισσότερα</a>
                </div>
            `).join("");

            // Ενημέρωση του HTML
            document.getElementById("news-container").innerHTML = newsHTML;

            // Αποθήκευση στην cache
            localStorage.setItem(cacheKey, newsHTML);
            localStorage.setItem(cacheTimeKey, now);
        })
        .catch(error => {
            console.error("Σφάλμα κατά τη λήψη ειδήσεων:", error);
        });
}
