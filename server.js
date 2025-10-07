  // server.js (tiny test)
  const express = require("express");
  const cors = require("cors");
  const fetch = require("node-fetch"); // keep if Node < 18

  const app = express();
  const PORT = 5050;
  const API_KEY = "7430f7a520c1cac7b670ccfb79e85e47f1ca2bfe";
  const BASE_URL = "https://www.giantbomb.com/api";

  app.use(cors());

  // health route — NOTE: path is just "/"
  app.get("/", (_req, res) => res.type("text").send("Game API is up"));

  // helper
  async function fetchFromGiantBomb(endpoint) {
    const url = `${BASE_URL}${endpoint}&api_key=${API_KEY}&format=json`;
    const r = await fetch(url, { headers: { "User-Agent": "Refund4LifeBot/1.0" } });
    if (!r.ok) throw new Error(`HTTP ${r.status} ${r.statusText}`);
    return r.json();
  }

  // NOTE: path is "/search", not a full URL
  app.get("/search", async (req, res) => {
    const q = (req.query.query || "").trim();
    if (!q) return res.status(400).json({ error: "Missing search query." });
    try {
      const data = await fetchFromGiantBomb(`/search/?query=${encodeURIComponent(q)}&resources=game`);
      if (!data.results?.length) return res.status(404).json({ error: "No games found." });
      res.json(data.results);
    } catch (e) {
      console.error(e.message);
      res.status(500).json({ error: "Search request failed." });
    }
  });

  // NOTE: path is "/details", not a full URL
  app.get("/details", async (req, res) => {
    const { id, type } = req.query;
    const valid = ["characters","locations","concepts","platforms","genres","releases","developers","publishers"];
    if (!id || !type) return res.status(400).json({ error: "Missing game ID or type." });
    if (!valid.includes(type)) return res.status(400).json({ error: "Invalid type parameter." });
    try {
      const data = await fetchFromGiantBomb(`/game/${id}/?field_list=${type}`);
      const list = data.results?.[type];
      if (!Array.isArray(list)) return res.json([]);
      res.json(list.map(x => ({ name: x.name, image: x.image?.icon_url || null })));
    } catch (e) {
      console.error(e.message);
      res.status(500).json({ error: "Details request failed." });
    }
  });

  app.listen(PORT, () => console.log(`API running at http://localhost:${PORT}`));
