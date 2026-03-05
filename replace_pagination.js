import fs from 'fs';

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(function(file) {
        file = dir + '/' + file;
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) {
            results = results.concat(walk(file));
        } else {
            if (file.endsWith('.vue')) {
                results.push(file);
            }
        }
    });
    return results;
}

const baseDir = 'C:/Users/Alvin/Documents/@@OJT/systems/hris_app/resources/js/pages';
const files = walk(baseDir);

let changed = 0;

files.forEach(f => {
    let code = fs.readFileSync(f, 'utf8');

    if (code.includes('.last_page > 1')) {
        const regex = /<div[^>]*v-if=\"([a-zA-Z0-9_]+)\.last_page > 1\"[\s\S]*?<\/div>\s*<\/div>/;
        let match = code.match(regex);

        if (match) {
            let varName = match[1];

            const blockRegex = new RegExp(`<div[^>]*v-if="${varName}\\.last_page > 1"[\\s\\S]*?</template>\\s*</div>`);

            const exactMatch = code.match(blockRegex);

            if (exactMatch) {
                const replacement = `<Pagination :meta="${varName}" />`;
                code = code.replace(blockRegex, replacement);

                // Add import if not exists
                if (!code.includes('import Pagination from')) {
                    code = code.replace(/<script setup lang="ts">/g, '<script setup lang="ts">\nimport Pagination from \'@/components/Pagination.vue\';');
                }

                fs.writeFileSync(f, code);
                console.log('REPLACED IN: ' + f + ' (variable: ' + varName + ')');
                changed++;
            } else {
                console.log('FAILED TO MATCH EXACT BLOCK IN: ' + f + ' (variable: ' + varName + ')');
            }
        } else {
            console.log('FAILED TO MATCH MAIN REGEX IN: ' + f);
        }
    }
});
console.log(`Finished processing. Changed ${changed} files.`);
