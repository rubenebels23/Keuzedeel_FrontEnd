const express = require("express");
const fetch = require("node-fetch");
const cors = require("cors");

const app = express();
const PORT = process.env.PORT || 5000;
const apiKey = "1268131de91b64d76ea5efdfc88fc7fec912efe1";
const BASE_URL = "https://www.giantbomb.com/api";

app.use(cors());

//  Utility: Unified API Fetch Function
async function fetchFromGiantBomb(endpoint) {
  const url = `${BASE_URL}${endpoint}&api_key=${apiKey}&format=json`;

  try {
    const response = await fetch(url, {
      headers: { "User-Agent": "Refund4LifeBot/1.0" },
    });

    if (!response.ok) throw new Error(`HTTP ${response.status} - ${response.statusText}`);
    return await response.json();
  } catch (error) {
    console.error("❌ Giant Bomb API fetch failed:", error.message);
    throw error;
  }
}

// 🔍 /search?query=skyrim
app.get("/search", async (req, res) => {
  const query = req.query.query;
  if (!query) return res.status(400).json({ error: "Missing search query." });

  try {
    const data = await fetchFromGiantBomb(`/search/?query=${encodeURIComponent(query)}&resources=game`);
    if (!data.results) return res.status(500).json({ error: "Unexpected API structure." });

    console.log("🔎 Search success:", data.results.map((g) => g.id).join(", "));
    res.json(data.results);
  } catch {
    res.status(500).json({ error: "Search request failed." });
  }
});

// 🔍 /details?id=gameId&type=characters
app.get("/details", async (req, res) => {
  const { id, type } = req.query;

  const validTypes = [
    "characters", "locations", "concepts", "platforms",
    "genres", "releases", "developers", "publishers"
  ];

  if (!id || !type) return res.status(400).json({ error: "Missing game ID or type." });
  if (!validTypes.includes(type)) return res.status(400).json({ error: "Invalid type parameter." });

  try {
    const data = await fetchFromGiantBomb(`/game/${id}/?field_list=${type}`);
    const results = data.results?.[type];

    if (!results || !Array.isArray(results)) {
      console.log(`📭 No ${type} found for game ID: ${id}`);
      return res.json([]);
    }

    const simplified = results.map(item => ({
      name: item.name,
      image: item.image?.icon_url || null,
    }));

    console.log(`📦 Fetched ${type} (${results.length}) for game ID ${id}`);
    res.json(simplified);
  } catch {
    res.status(500).json({ error: "Details request failed." });
  }
});

// ✅ Server Running
app.listen(PORT, () => {
  console.log(`🚀 API running at http://localhost:${PORT}`);
});
