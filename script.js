// === API base (match your Node server port) ===
const API = "http://localhost:5050"; // change if your server uses another port

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

// Small helper so errors are handled consistently
async function getJSON(url) {
  const res = await fetch(url);
  if (!res.ok) {
    // Try to show server error message if available
    let msg = `HTTP ${res.status}`;
    try {
      const j = await res.json();
      if (j?.error) msg += ` - ${j.error}`;
    } catch (_) {}
    throw new Error(msg);
  }
  return res.json();
}

// Search Function 
async function searchGame() {
  const query = elements.searchInput.value?.trim();
  if (!query) return;

  elements.results.innerHTML = `<p class="text-gray-300">Searching…</p>`;

  try {
    const data = await getJSON(`${API}/search?query=${encodeURIComponent(query)}`);

    elements.results.innerHTML = "";

    data.forEach((game) => {
      const card = document.createElement("div");
      card.className =
        "bg-black bg-opacity-30 rounded-lg p-4 hover:shadow-lg transition cursor-pointer flex flex-col items-center text-center";

      const img = game.image?.icon_url ||
        "https://upload.wikimedia.org/wikipedia/commons/6/65/No-Image-Placeholder.svg";

      card.innerHTML = `
        <img src="${img}" alt="${game.name}" class="w-24 h-24 object-cover rounded mb-2" />
        <h2 class="text-lg font-semibold">${game.name}</h2>
        <p class="text-sm text-gray-300">${game.deck || "No description available."}</p>
      `;
      card.onclick = () => openModal(game);
      elements.results.appendChild(card);
    });

    if (data.length === 0) {
      elements.results.innerHTML = `<p class="text-gray-300">No games found.</p>`;
    }
  } catch (err) {
    console.error("Search failed:", err);
    elements.results.innerHTML = `<p class="text-red-400">Search failed: ${err.message}. Is the API running on ${API}?</p>`;
  }
}

// === Modal Open Function ===
function openModal(game) {
  const match = game.api_detail_url?.match(/\/game\/([^/]+)\//);
  window.currentGameId = match ? match[1] : game.id;

  elements.modalTitle.textContent = game.name;
  elements.modalDesc.textContent = game.deck || "No description.";
  elements.formGameId.value = window.currentGameId || "";
  elements.formGameTitle.value = game.name || "";
  elements.formGameThumb.value = game.image?.icon_url || "";
  elements.formGamePrice.value = 50;
  elements.modalResults.innerHTML = "";

  elements.modal.classList.remove("hidden");
}

// === Fetch Extra Info ===
async function fetchExtra(category) {
  if (!window.currentGameId) return;

  const container = elements.modalResults;
  container.innerHTML = `<p class="text-gray-300">Loading ${category}…</p>`;

  try {
    const data = await getJSON(`${API}/details?id=${encodeURIComponent(window.currentGameId)}&type=${encodeURIComponent(category)}`);

    container.innerHTML = `<h3 class="text-lg font-bold mb-2">${capitalize(category)}:</h3>`;

    if (!Array.isArray(data) || data.length === 0) {
      container.innerHTML += `<p class="text-gray-300">No ${category} found.</p>`;
      return;
    }

    const list = document.createElement("div");
    list.className = "extra-items grid grid-cols-2 sm:grid-cols-3 gap-4";

    data.forEach((item) => {
      const img = item.image ||
        "https://upload.wikimedia.org/wikipedia/commons/6/65/No-Image-Placeholder.svg";
      const itemDiv = document.createElement("div");
      itemDiv.className =
        "flex flex-col items-center text-center bg-purple-900 bg-opacity-20 rounded p-2";
      itemDiv.innerHTML = `
        <img src="${img}" alt="${item.name}" class="w-16 h-16 object-cover rounded mb-1" />
        <span class="text-white text-sm">${item.name}</span>
      `;
      list.appendChild(itemDiv);
    });

    container.appendChild(list);
  } catch (err) {
    console.error(err);
    container.innerHTML = `<p class="text-red-400">Failed to load ${category}: ${err.message}</p>`;
  }
}

// expose for buttons that use onclick="fetchExtra('characters')" etc.
window.fetchExtra = fetchExtra;

// === Utility ===
function capitalize(str) {
  return str ? str.charAt(0).toUpperCase() + str.slice(1) : "";
}

// === Event Listeners ===
document.addEventListener("DOMContentLoaded", () => {
  if (elements.searchIcon) {
    elements.searchIcon.addEventListener("click", searchGame);
  }

  if (elements.searchInput) {
    elements.searchInput.addEventListener("keydown", (e) => {
      if (e.key === "Enter") searchGame();
    });
  }

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      elements.modal.classList.add("hidden");
    }
  });

  if (elements.closeModalBtn) {
    elements.closeModalBtn.addEventListener("click", () => {
      elements.modal.classList.add("hidden");
    });
  }
});
