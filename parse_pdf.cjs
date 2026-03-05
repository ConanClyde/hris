import fs from 'fs';
import pdf from 'pdf-parse';

const dataBuffer = fs.readFileSync('storage/app/prompts/csc_leave_rules.pdf');

pdf(dataBuffer)
    .then(function (data) {
        fs.writeFileSync('storage/app/prompts/csc_leave_rules.txt', data.text);
        console.log('Successfully extracted PDF text');
    })
    .catch(function (error) {
        console.error('Error parsing PDF:', error);
    });
