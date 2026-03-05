// Extract file path from command line arguments
const filePath = process.argv[2];

if (!filePath) {
    console.error(JSON.stringify({ error: 'Please provide a path to a PDF file.' }));
    process.exit(1);
}

const loadModules = async () => {
    const fsModule = await import('fs');
    const pathModule = await import('path');
    const pdfModule = await import('pdf-parse');

    return {
        fs: fsModule.default ?? fsModule,
        path: pathModule.default ?? pathModule,
        pdf: pdfModule.default ?? pdfModule,
    };
};

const run = async () => {
    try {
        const { fs, path, pdf } = await loadModules();
        const absolutePath = path.resolve(filePath);

        if (!fs.existsSync(absolutePath)) {
            console.error(JSON.stringify({ error: 'File does not exist: ' + absolutePath }));
            process.exit(1);
        }

        const dataBuffer = fs.readFileSync(absolutePath);

        try {
            const data = await pdf(dataBuffer);
            console.log(JSON.stringify({ success: true, text: data.text }));
        } catch (error) {
            console.error(JSON.stringify({ error: 'Error parsing PDF: ' + error.message }));
            process.exit(1);
        }
    } catch (error) {
        console.error(JSON.stringify({ error: 'An unexpected error occurred: ' + error.message }));
        process.exit(1);
    }
};

run();
