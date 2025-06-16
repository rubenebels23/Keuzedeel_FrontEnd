// === Cached DOM Elements ===
const elements = {
  searchInput: document.getElementById("searchInput"),
  searchIcon: document.getElementById("search-icon"),
  results: document.getElementById("results"),
  modal: document.getElementById("gameModal"),
  modalTitle: document.getElementById("modal-title"),
  modalDesc: document.getElementById("modal-description"),
  modalResults: document.getElementById("modal-results"),
  closeModalBtn: document.querySelector(".close-button"),
  formGameId: document.getElementById("formGameId"),
  formGameTitle: document.getElementById("formGameTitle"),
  formGameThumb: document.getElementById("formGameThumb"),
  formGamePrice: document.getElementById("formGamePrice"),
};

// === Search Function ===
async function searchGame() {
  const query = elements.searchInput.value;
  const response = await fetch(
    `http://localhost:5000/search?query=${encodeURIComponent(query)}`
  );
  const data = await response.json();

  elements.results.innerHTML = "";

  data.forEach((game) => {
    const card = document.createElement("div");
    card.className =
      "bg-black bg-opacity-30 rounded-lg p-4 hover:shadow-lg transition cursor-pointer flex flex-col items-center text-center";
    card.innerHTML = `
      <img src="${game.image?.icon_url}" alt="${game.name}" class="w-24 h-24 object-cover rounded mb-2" />
      <h2 class="text-lg font-semibold">${game.name}</h2>
      <p class="text-sm text-gray-300">${game.deck || "No description available."}</p>
    `;
    card.onclick = () => openModal(game);
    elements.results.appendChild(card);
  });
}

// === Modal Open Function ===
function openModal(game) {
  const match = game.api_detail_url.match(/\/game\/([^/]+)\//);
  window.currentGameId = match ? match[1] : game.id;

  elements.modalTitle.textContent = game.name;
  elements.modalDesc.textContent = game.deck || "No description.";
  elements.formGameId.value = window.currentGameId;
  elements.formGameTitle.value = game.name;
  elements.formGameThumb.value = game.image?.icon_url || "";
  elements.formGamePrice.value = 50;
  elements.modalResults.innerHTML = "";

  elements.modal.classList.remove("hidden");
}

// === Fetch Extra Info ===
async function fetchExtra(category) {
  const container = elements.modalResults;
  container.innerHTML = `<p>Loading...</p>`;

  try {
    const response = await fetch(
      `http://localhost:5000/details?id=${window.currentGameId}&type=${category}`
    );
    if (!response.ok) throw new Error(`HTTP error: ${response.status}`);

    const data = await response.json();
    container.innerHTML = `<h3 class="text-lg font-bold mb-2">${capitalize(category)}:</h3>`;

    if (data.length === 0) {
      container.innerHTML += `<p>No ${category} found.</p>`;
    } else {
      const list = document.createElement("div");
      list.className = "extra-items grid grid-cols-2 sm:grid-cols-3 gap-4";

      data.forEach((item) => {
        const itemDiv = document.createElement("div");
        itemDiv.className =
          "flex flex-col items-center text-center bg-purple-900 bg-opacity-20 rounded p-2";
        itemDiv.innerHTML = `
          <img 
            src="${item.image || 'https://upload.wikimedia.org/wikipedia/commons/6/65/No-Image-Placeholder.svg'}" 
            alt="${item.name}" 
            class="w-16 h-16 object-cover rounded mb-1"
          />
          <span class="text-white text-sm">${item.name}</span>
        `;
        list.appendChild(itemDiv);
      });

      container.appendChild(list);
    }
  } catch (err) {
    console.error(err);
    alert("Failed to fetch data. Please try again.");
  }
}

// === Utility ===
function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

// === Event Listeners ===
document.addEventListener("DOMContentLoaded", () => {
  elements.searchIcon.addEventListener("click", searchGame);

  elements.searchInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      searchGame();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      elements.modal.classList.add("hidden");
    }
  });

  elements.closeModalBtn.addEventListener("click", () => {
    elements.modal.classList.add("hidden");
  });
});
