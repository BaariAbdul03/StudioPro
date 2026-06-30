import { stitch } from "@google/stitch-sdk";

async function main() {
    const apiKey = process.env.STITCH_API_KEY;
    const projectId = process.env.STITCH_PROJECT_ID;
    const prompt = process.env.STITCH_PROMPT;

    if (!apiKey) {
        console.error("Error: STITCH_API_KEY is not set.");
        process.exit(1);
    }
    if (!projectId) {
        console.error("Error: STITCH_PROJECT_ID is not set.");
        process.exit(1);
    }
    if (!prompt) {
        console.error("Error: STITCH_PROMPT is not set.");
        process.exit(1);
    }

    try {
        // Initialize stitch project
        const project = stitch.project(projectId);

        // Generate the screen
        const screen = await project.generate(prompt);

        // Get HTML download URL
        const htmlUrl = await screen.getHtml();
        if (!htmlUrl) {
            throw new Error("No HTML URL returned by Stitch SDK.");
        }

        // Fetch HTML contents
        let rawHtml = '';
        if (htmlUrl.startsWith('http://') || htmlUrl.startsWith('https://')) {
            const res = await fetch(htmlUrl);
            if (!res.ok) {
                throw new Error(`Failed to fetch HTML from URL ${htmlUrl}: ${res.statusText}`);
            }
            rawHtml = await res.text();
        } else {
            rawHtml = htmlUrl;
        }

        // Extract style rules and body markup
        let css = '';
        const styleRegex = /<style[^>]*>([\s\S]*?)<\/style>/gi;
        let match;
        // Collect all styles
        while ((match = styleRegex.exec(rawHtml)) !== null) {
            css += match[1] + '\n';
        }
        css = css.trim();

        // Collect body HTML markup
        const bodyRegex = /<body[^>]*>([\s\S]*?)<\/body>/i;
        const bodyMatch = bodyRegex.exec(rawHtml);
        let html = '';
        if (bodyMatch) {
            html = bodyMatch[1].trim();
        } else {
            // Remove style tags and use the remaining HTML
            html = rawHtml.replace(styleRegex, '').trim();
        }

        // Print final JSON structure to stdout
        console.log(JSON.stringify({
            html,
            css
        }));
    } catch (err) {
        console.error("Bridge Error: " + err.message);
        process.exit(1);
    }
}

main();
