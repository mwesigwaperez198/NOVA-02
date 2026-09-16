<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>You're Offline - QuickShop</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: sans-serif; background: #f9fafb; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem; }
    .card { background: white; border-radius: 1.5rem; padding: 2.5rem; text-align: center; max-width: 400px; width: 100%; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
    .icon { font-size: 4rem; margin-bottom: 1rem; }
    h1 { font-size: 1.5rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; }
    p { color: #6b7280; margin-bottom: 1.5rem; line-height: 1.6; }
    button { background: #ef4444; color: white; border: none; padding: 0.875rem 2rem; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; width: 100%; }
    button:hover { background: #dc2626; }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon">ðŸ“¶</div>
    <h1>You're Offline</h1>
    <p>No internet connection. Previously visited pages are still available. Connect to browse new products.</p>
    <button onclick="window.location.reload()">Try Again</button>
  </div>
</body>
</html>

