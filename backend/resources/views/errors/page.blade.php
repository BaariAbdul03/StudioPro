@php
    $problems = [
        403 => ['Access denied', 'Your account does not have permission to open this page.'],
        404 => ['Page not found', 'The address may have changed, or the page may have been removed.'],
        500 => ['Something went wrong', 'StudioPro could not complete the request. Try opening the page again.'],
        503 => ['Workspace unavailable', 'The workspace may be starting up. Give it a moment and try again.'],
    ];
    [$title, $description] = $problems[$status];
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="robots" content="noindex">
    <script src="/appearance.js"></script>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <title>{{ $title }} — StudioPro</title>
    <style>
        :root { color-scheme: light dark; }
        html[data-studio-theme="light"] { color-scheme: light; }
        html[data-studio-theme="dark"] { color-scheme: dark; }
        body { margin: 0; min-height: 100vh; min-height: 100svh; display: flex; flex-direction: column; font-family: system-ui, sans-serif; background: Canvas; background: light-dark(#f3f5f9, #11151d); color: CanvasText; }
        main { flex: 1; display: grid; place-items: center; padding: 1rem; }
        section { display: grid; grid-template-columns: 1fr 1.65fr; width: min(100%, 710px); border: 1px solid GrayText; border-color: light-dark(#d8e0ec, #364255); border-radius: 14px; overflow: hidden; background: Canvas; background: light-dark(#fff, #1b222d); }
        .code { display: grid; place-items: center; min-height: 8rem; font: 650 clamp(2.75rem, 8vw, 5rem)/1 monospace; background: light-dark(#e6edfc, #182437); color: light-dark(#2549cc, #adc4ff); }
        .content { padding: clamp(1.15rem, 4vw, 2.5rem); }
        h1 { margin: .6rem 0; font-size: clamp(1.6rem, 4vw, 2.3rem); line-height: 1.1; letter-spacing: -.04em; }
        p { line-height: 1.55; color: light-dark(#53627e, #b1bdd0); }
        .actions, nav { display: flex; flex-wrap: wrap; align-items: center; gap: .6rem 1rem; }
        a { color: LinkText; color: light-dark(#2549cc, #adc4ff); text-underline-offset: .2em; }
        a:focus-visible { outline-offset: 3px; }
        .primary { display: inline-block; padding: .55rem .8rem; border-radius: 7px; background: #2549cc; color: white; text-decoration: none; }
        footer { border-top: 1px solid GrayText; border-color: light-dark(#d8e0ec, #364255); padding: .85rem 1rem; }
        nav { justify-content: center; font-size: .85rem; }
        @media (max-width: 530px) { section { grid-template-columns: minmax(0, 1fr); } }
    </style>
</head>
<body>
<main>
    <section aria-labelledby="error-title">
        <div class="code" aria-hidden="true">{{ $status }}</div>
        <div class="content">
            <strong>StudioPro</strong>
            <h1 id="error-title">{{ $title }}</h1>
            <p>{{ $description }}</p>
            <div class="actions">
                <a class="primary" href="/">Go home</a>
                <a href="/demo">Open demo</a>
            </div>
        </div>
    </section>
</main>
<footer>
    <nav aria-label="Site information">
        <a href="/welcome#faq">FAQ</a>
        <a href="/privacy">Privacy policy</a>
        <a href="/about">About us</a>
        <a href="/terms">Terms &amp; conditions</a>
    </nav>
</footer>
</body>
</html>