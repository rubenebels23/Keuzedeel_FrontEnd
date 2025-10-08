// server.js (tiny test)
require("dotenv").config({ path: require("path").join(__dirname, ".env") });
if (!process.env.GIANTBOMB_TOKEN) {

  process.exit(1);
}

const express = require("express");
const cors = require("cors");
const fetch = require("node-fetch"); // keep if Node < 18

const app = express();
const PORT = 5050;
const API_KEY = process.env.GIANTBOMB_TOKEN;
const BASE_URL = "https://www.giantbomb.com/api";

app.use(cors());

// health route — NOTE: path is just "/"
app.get("/", (_req, res) => res.type("text").send("Game API is up"));

// helper
async function fetchFromGiantBomb(endpoint) {
  const url = `${BASE_URL}${endpoint}&api_key=${API_KEY}&format=json`;
  const r = await fetch(url, {
    headers: { "User-Agent": "Refund4LifeBot/1.0" },
  });
  if (!r.ok) throw new Error(`HTTP ${r.status} ${r.statusText}`);
  return r.json();
}

// NOTE: path is "/search", not a full URL
app.get('/search', async (req, res) => {
  try {
    const q = (req.query.query || '').trim();
    if (!q) return res.status(400).json({ error: 'Missing query' });

    const url = new URL('https://www.giantbomb.com/api/search/');
    url.searchParams.set('api_key', process.env.GIANTBOMB_TOKEN);
    url.searchParams.set('format', 'json');
    url.searchParams.set('resources', 'game');
    url.searchParams.set('query', q);
    url.searchParams.set('limit', '10');

    const r = await fetch(url.toString(), {
      headers: { 'User-Agent': 'RefundSite/1.0', 'Accept': 'application/json' }
    });
    const text = await r.text();
    if (!r.ok) {
      return res.status(r.status).json({ error: `GiantBomb ${r.status} ${r.statusText}: ${text.slice(0,200)}` });
    }
    const data = JSON.parse(text);
    res.json(Array.isArray(data?.results) ? data.results : []);
  } catch (err) {
    res.status(500).json({ error: err.message || 'Unknown error' });
  }
});

// NOTE: path is "/details", not a full URL
app.get("/details", async (req, res) => {
  const { id, type } = req.query;
  const valid = [
    "characters",
    "locations",
    "concepts",
    "platforms",
    "genres",
    "releases",
    "developers",
    "publishers",
  ];
  if (!id || !type)
    return res.status(400).json({ error: "Missing game ID or type." });
  if (!valid.includes(type))
    return res.status(400).json({ error: "Invalid type parameter." });
  try {
    const data = await fetchFromGiantBomb(`/game/${id}/?field_list=${type}`);
    const list = data.results?.[type];
    if (!Array.isArray(list)) return res.json([]);
    res.json(
      list.map((x) => ({ name: x.name, image: x.image?.icon_url || null })),
    );
  } catch (e) {
    console.error(e.message);
    res.status(500).json({ error: "Details request failed." });
  }
});

app.listen(PORT, () => console.log(`API running at http://localhost:${PORT}`));
