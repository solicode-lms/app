const fs = require('fs');

const uasRaw = fs.readFileSync('modules/PkgCompetences/Database/data/uniteApprentissages.csv', 'utf8');
const uas = uasRaw.split('\n')
  .filter(l => l.trim())
  .map(l => {
    const cols = l.split('","');
    if(cols.length > 6) return cols[6].replace(/"/g, '').trim();
    return '';
  }).filter(Boolean);

console.log("UAs found:", uas.length);

const chapitresRaw = fs.readFileSync('modules/PkgCompetences/Database/data/chapitres.csv', 'utf8');
const chapitres = chapitresRaw.split('\n');
const newChap = chapitres.filter((l, i) => {
  if (i === 0 || !l.trim()) return true;
  const cols = l.split('","');
  const uaRef = cols[3] ? cols[3].replace(/"/g, '').trim() : '';
  return uas.includes(uaRef);
});

console.log("Chapitres removed:", chapitres.length - newChap.length);
fs.writeFileSync('modules/PkgCompetences/Database/data/chapitres.csv', newChap.join('\n'));
