---
# author:
date: 06-02-2025 17:42
link: https://bsky.app/profile/jhey.dev/post/3lhhi3x4qnk2k
taxonomy:
    category: link
title: "Dynamic table highlighting with CSS :has"
# via:
---

In this BlueSky post the user shows some crazy CSS that allows for dynamic table highlighting.
The CSS included in that post was this:

```css
td:has(~ td:hover), /* previous sibling cells */
table:has(td:nth-of-type(3):hover) /* column cells */
tr:not(:first-of-type):has(~ tr:hover)
td:nth-of-type(3) {
    background: var(--highlighted);
}
```

This CSS should highlight a row up to the cell that is hovered and it should also highlight a column up to the cell that is hovered.
After removing the `:not(:first-of-type)` because I have proper table headers, the CSS seems to do the highlighting well for any row but only works for the 3rd column, as the example table below shows.
I'm not sure yet if there's a way to _not_ hardcode the 3 in the CSS to work for any column independently or if I'd have to write selectors explicitly for all columns.

<style>
    :root {
        --highlighted: yellow;
    }

    td:has(~ td:hover), /* previous sibling cells */
    table:has(td:nth-of-type(3):hover) /* column cells */
    tr:has(~ tr:hover)
    td:nth-of-type(3) {
        background: var(--highlighted);
    }
</style>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>English</th>
            <th>Portuguese</th>
            <th>French</th>
            <th>German</th>
            <th>Italian</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>1</td><td>January</td><td>Janeiro</td><td>Janvier</td><td>Januar</td><td>Gennaio</td></tr>
        <tr><td>2</td><td>February</td><td>Fevereiro</td><td>Février</td><td>Februar</td><td>Febbraio</td></tr>
        <tr><td>3</td><td>March</td><td>Março</td><td>Mars</td><td>März</td><td>Marzo</td></tr>
        <tr><td>4</td><td>April</td><td>Abril</td><td>Avril</td><td>April</td><td>Aprile</td></tr>
        <tr><td>5</td><td>May</td><td>Maio</td><td>Mai</td><td>Mai</td><td>Maggio</td></tr>
        <tr><td>6</td><td>June</td><td>Junho</td><td>Juin</td><td>Juni</td><td>Giugno</td></tr>
        <tr><td>7</td><td>July</td><td>Julho</td><td>Juillet</td><td>Juli</td><td>Luglio</td></tr>
        <tr><td>8</td><td>August</td><td>Agosto</td><td>Août</td><td>August</td><td>Agosto</td></tr>
        <tr><td>9</td><td>September</td><td>Setembro</td><td>Septembre</td><td>September</td><td>Settembre</td></tr>
        <tr><td>10</td><td>October</td><td>Outubro</td><td>Octobre</td><td>Oktober</td><td>Ottobre</td></tr>
        <tr><td>11</td><td>November</td><td>Novembro</td><td>Novembre</td><td>November</td><td>Novembre</td></tr>
        <tr><td>12</td><td>December</td><td>Dezembro</td><td>Décembre</td><td>Dezember</td><td>Dicembre</td></tr>
    </tbody>
</table>


