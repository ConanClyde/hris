<style>
    /* Scoped styles for C1 */
    #C1 .pds-wrapper {
        font-family: 'Arial Narrow', 'Roboto Condensed', sans-serif-condensed, sans-serif;
        font-stretch: condensed;
        background-color: white;
        width: 100%;
        max-width: 816px;
        /* A4 width */
        margin: 0 auto;
        box-sizing: border-box;
        padding: 0.1in;
        /* Match pds-page padding from styles */
    }

    /* Grid Sections */
    #C1 .pds-title-section {
        display: grid;
        grid-template-columns: repeat(10, 1fr);
        grid-template-rows: min-content min-content min-content min-content min-content min-content min-content;
        width: 100%;
        border: 2px solid #000;
        border-bottom: none;
        background-color: white;
        gap: 0;
    }

    #C1 :where(.pds-title-section)>div {
        border: none;
        box-sizing: border-box;
        background-color: white;
    }

    /* Font styles */
    #C1 .form-number {
        font-family: Calibri, sans-serif;
        font-style: italic;
        font-weight: bold;
        font-size: 11pt;
        line-height: 1;
    }

    #C1 .form-version {
        font-family: Calibri, sans-serif;
        font-style: italic;
        font-weight: bold;
        font-size: 9pt;
        line-height: 1;
    }

    #C1 .form-title {
        font-family: 'Arial Black', sans-serif;
        font-size: 22pt;
        margin: 0;
        margin-top: -15px;
        line-height: 1;
    }

    #C1 .warning-text {
        font-family: 'Arial Narrow', sans-serif;
        font-weight: bold;
        font-style: italic;
        font-size: 6.5pt;
        line-height: 1;
        display: block;
    }

    #C1 .guide-text {
        font-family: 'Arial Narrow', sans-serif;
        font-weight: bold;
        font-style: italic;
        font-size: 7pt;
        line-height: 1;
        display: block;
    }

    #C1 .instruction-text {
        font-family: 'Arial Narrow', sans-serif;
        font-size: 8pt;
        line-height: 1;
        display: inline-block;
    }

    #C1 .instruction-text input[type="checkbox"] {
        width: 9px;
        height: 9px;
        vertical-align: middle;
        margin: 0;
    }

    #C1 .instruction-bold {
        font-family: 'Arial Narrow', sans-serif;
        font-weight: bold;
        font-size: 8pt;
        line-height: 1;
    }

    #C1 .personal-info-text {
        font-family: 'Arial Narrow', sans-serif;
        font-size: 7pt;
    }

    #C1 .personal-info-extension {
        font-family: 'Arial Narrow', sans-serif;
        font-size: 6pt;
    }

    /* Title Grid Areas */
    #C1 .title-div1 {
        grid-area: 1 / 1 / 2 / 11;
        height: auto;
        padding: 2px;
    }

    #C1 .title-div2 {
        grid-area: 2 / 3 / 4 / 9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #C1 .title-div3 {
        grid-area: 2 / 1 / 4 / 3;
        height: auto;
    }

    #C1 .title-div5 {
        grid-area: 2 / 9 / 4 / 11;
        height: auto;
    }

    #C1 .title-div6 {
        grid-area: 4 / 1 / 5 / 11;
        height: auto;
        padding: 0;
        border-bottom: none;
        border-top: none;
    }

    #C1 .title-div7 {
        grid-area: 5 / 1 / 6 / 11;
        height: auto;
    }

    #C1 .title-div8 {
        grid-area: 6 / 1 / 7 / 11;
        height: auto;
        padding: 0;
        border-bottom: none;
        border-top: none;
    }

    #C1 .title-div9 {
        grid-area: 7 / 1 / 8 / 11;
        height: auto;
        padding: 0;
        border-bottom: none;
        border-top: none;
    }

    /* Personal Information Section */
    #C1 .pds-personal-info-section {
        display: grid;
        grid-template-columns: repeat(60, 1fr);
        width: 100%;
        border: 2px solid #000;
        border-top: 2px solid #000;
        border-bottom: none;
        margin-top: 0;
        background-color: #000;
        gap: 1px;
    }

    #C1 :where(.pds-personal-info-section)>div {
        border: none;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        min-height: 15px;
        /* Minimum height for rows */
        background-color: white;
    }

    /* Info Areas */
    #C1 .info-div1 {
        grid-area: 1 / 1 / 2 / 61;
        padding-left: 5px;
        background-color: #969696;
        font-family: 'Arial Narrow', sans-serif;
        color: white;
        font-weight: bold;
        font-style: italic;
        font-size: 11pt;
    }

    #C1 .info-num1,
    #C1 .info-num2,
    #C1 .info-num3 {
        justify-content: flex-end;
        padding-right: 2px;
        background-color: #eaeaea;
    }

    #C1 .info-num1 {
        grid-area: 2 / 1 / 3 / 2;
    }

    #C1 .info-num2 {
        grid-area: 3 / 1 / 4 / 2;
    }

    #C1 .info-num3 {
        grid-area: 4 / 1 / 5 / 2;
    }

    #C1 .info-div2,
    #C1 .info-div3,
    #C1 .info-div4 {
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .info-div2 {
        grid-area: 2 / 2 / 3 / 11;
    }

    #C1 .info-div3 {
        grid-area: 3 / 2 / 4 / 11;
    }

    #C1 .info-div4 {
        grid-area: 4 / 2 / 5 / 11;
    }

    #C1 .info-div5 {
        grid-area: 2 / 11 / 3 / 61;
    }

    #C1 .info-div8 {
        grid-area: 3 / 11 / 4 / 47;
    }

    #C1 .info-div7 {
        grid-area: 3 / 47 / 4 / 61;
        background-color: #eaeaea;
        padding-left: 2px;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }

    #C1 .info-div6 {
        grid-area: 4 / 11 / 5 / 61;
    }

    #C1 .info-div5,
    #C1 .info-div6,
    #C1 .info-div8 {
        font-family: 'Arial Narrow', sans-serif;
        font-size: 10pt;
        font-weight: bold;
        padding-left: 5px;
    }

    /* Merged Extension Items */
    #C1 .div1,
    #C1 .div5,
    #C1 .div9,
    #C1 .div44,
    #C1 .div67,
    #C1 .div71,
    #C1 .div72,
    #C1 .div73 {
        justify-content: flex-end;
        padding-right: 2px;
        background-color: #eaeaea;
        font-family: 'Arial Narrow', sans-serif;
        font-size: 7pt;
        position: relative;
        z-index: 1;
        box-shadow: 1px 0 0 0 #eaeaea;
    }

    #C1 .div17,
    #C1 .div18,
    #C1 .div19,
    #C1 .div42,
    #C1 .div43,
    #C1 .info-num1, #C1 .info-num2, #C1 .info-num3 {
         background-color: #eaeaea;
         justify-content: flex-end;
         padding-right: 5px;
         font-weight: bold;
         font-size: 7pt;
         font-family: 'Arial Narrow', sans-serif;
         position: relative;
         z-index: 1;
         box-shadow: 1px 0 0 0 #eaeaea;
    }

    #C1 .div2,
    #C1 .div7,
    #C1 .div10,
    #C1 .div20,
    #C1 .div26,
    #C1 .div21,
    #C1 .div22,
    #C1 .div28,
    #C1 .div45,
    #C1 .div46,
    #C1 .div47,
    #C1 .div51,
    #C1 .div68,
    #C1 .div70,
    #C1 .div74,
    #C1 .div75,
    #C1 .div76,
    #C1 .div80,
    #C1 .div81,
    #C1 .div82 {
        background-color: #eaeaea;
        padding-left: 5px;
        font-family: 'Arial Narrow', sans-serif;
        font-size: 7pt;
    }

    #C1 .div1 {
        grid-area: 5 / 1 / 6 / 2;
    }

    #C1 .div2 {
        grid-area: 5 / 2 / 6 / 11;
    }

    #C1 .div4 {
        grid-area: 5 / 11 / 6 / 25;
        padding-left: 5px;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
    }

    /* DOB */

    #C1 .div5 {
        grid-area: 6 / 1 / 7 / 2;
    }

    #C1 .div7 {
        grid-area: 6 / 2 / 7 / 11;
    }

    #C1 .div8 {
        grid-area: 6 / 11 / 7 / 25;
        padding-left: 5px;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
    }

    /* POB */

    #C1 .div9 {
        grid-area: 7 / 1 / 8 / 2;
    }

    #C1 .div10 {
        grid-area: 7 / 2 / 8 / 11;
    }

    #C1 .div11 {
        grid-area: 7 / 11 / 8 / 25;
    }

    /* Sex */

    #C1 .div12 {
        grid-area: 5 / 25 / 8 / 39;
        background-color: #eaeaea;
        padding-left: 5px;
        font-family: 'Arial Narrow', sans-serif;
        font-size: 7pt;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        padding-top: 5px;
    }

    /* div15 and div16 removed */

    #C1 .div13 {
        grid-area: 5 / 39 / 7 / 61;
        padding: 2px;
    }

    /* Citizenship Options */
    #C1 .div14 {
        grid-area: 7 / 39 / 8 / 61;
        padding-left: 5px;
        font-weight: bold;
        font-size: 10pt;
    }

    /* Citizenship Country */

    /* Checkbox Styles */
    #C1 .checkbox-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 5px;
        width: 100%;
    }

    #C1 .checkbox-item {
        display: flex;
        align-items: center;
        margin-right: 5px;
        font-size: 7pt;
        white-space: nowrap;
        font-family: 'Arial Narrow', sans-serif;
    }

    #C1 .checkbox-item input[type="checkbox"] {
        margin: 0 3px 0 0;
        width: 10px;
        height: 10px;
    }

    #C1 .checkbox-col {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Rows 8-12 */
    #C1 .div17 {
        grid-area: 8 / 1 / 12 / 2;
    }

    #C1 .div20 {
        grid-area: 8 / 2 / 12 / 11;
    }

    #C1 .div23 {
        grid-area: 8 / 11 / 12 / 25;
        align-items: flex-start;
        padding: 5px;
    }

    /* Civil Status */
    #C1 .div26 {
        grid-area: 8 / 25 / 14 / 35;
        position: relative;
        z-index: 1;
        border-top: 1px solid #eaeaea;
        border-left: 1px solid #eaeaea;
        border-right: 1px solid #eaeaea;
        border-bottom: none;
    }

    /* Res Address Label */
    #C1 .div27 {
        border-top: none;
    }

    #C1 .div40 {
        grid-area: 8 / 35 / 9 / 47;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div41 {
        grid-area: 8 / 47 / 9 / 61;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div29 {
        grid-area: 9 / 35 / 10 / 47;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div30 {
        grid-area: 9 / 47 / 10 / 61;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div31 {
        grid-area: 10 / 35 / 11 / 47;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div32 {
        grid-area: 10 / 47 / 11 / 61;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div33 {
        grid-area: 11 / 35 / 12 / 47;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div34 {
        grid-area: 11 / 47 / 12 / 61;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div35 {
        grid-area: 12 / 35 / 13 / 47;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div36 {
        grid-area: 12 / 47 / 13 / 61;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div37 {
        grid-area: 13 / 35 / 14 / 47;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div38 {
        grid-area: 13 / 47 / 14 / 61;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    /* Height/Weight */
    #C1 .div18 {
        grid-area: 12 / 1 / 14 / 2;
    }

    #C1 .div21 {
        grid-area: 12 / 2 / 14 / 11;
    }

    #C1 .div24 {
        grid-area: 12 / 11 / 14 / 25;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    /* div27 removed */

    #C1 .div19 {
        grid-area: 14 / 1 / 15 / 2;
    }

    #C1 .div22 {
        grid-area: 14 / 2 / 15 / 11;
    }

    #C1 .div25 {
        grid-area: 14 / 11 / 15 / 25;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div28 {
        grid-area: 14 / 25 / 15 / 35;
        justify-content: center;
        position: relative;
        z-index: 1;
        box-shadow: 0 -1px 0 0 #eaeaea;
    }

    /* ZIP CODE Label */
    #C1 .div39 {
        grid-area: 14 / 35 / 15 / 61;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    /* ZIP Input */

    /* IDs Section */
    #C1 .div42 {
        grid-area: 15 / 1 / 17 / 2;
    }

    #C1 .div43 {
        grid-area: 17 / 1 / 19 / 2;
    }

    #C1 .div44 {
        grid-area: 19 / 1 / 21 / 2;
    }

    #C1 .div45 {
        grid-area: 15 / 2 / 17 / 11;
    }

    /* Blood Type Label */
    #C1 .div46 {
        grid-area: 17 / 2 / 19 / 11;
    }

    /* UMID */
    #C1 .div47 {
        grid-area: 19 / 2 / 21 / 11;
    }

    /* PAGIBIG */
    #C1 .div48 {
        grid-area: 15 / 11 / 17 / 25;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div49 {
        grid-area: 17 / 11 / 19 / 25;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div50 {
        grid-area: 19 / 11 / 21 / 25;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div51 {
        grid-area: 15 / 25 / 21 / 35;
    }

    /* Permanent Address Label */

    /* Perm Address Fields */
    #C1 .div54 {
        grid-area: 15 / 35 / 16 / 47;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div55 {
        grid-area: 15 / 47 / 16 / 61;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div56 {
        grid-area: 16 / 35 / 17 / 47;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div57 {
        grid-area: 16 / 47 / 17 / 61;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div58 {
        grid-area: 17 / 35 / 18 / 47;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div59 {
        grid-area: 17 / 47 / 18 / 61;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div60 {
        grid-area: 18 / 35 / 19 / 47;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div61 {
        grid-area: 18 / 47 / 19 / 61;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div62 {
        grid-area: 19 / 35 / 20 / 47;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div63 {
        grid-area: 19 / 47 / 20 / 61;
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .div64 {
        grid-area: 20 / 35 / 21 / 47;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div65 {
        grid-area: 20 / 47 / 21 / 61;
        justify-content: center;
        font-size: 6pt;
        font-style: italic;
    }

    #C1 .div66 {
        grid-area: 21 / 35 / 22 / 61;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div67 {
        grid-area: 21 / 1 / 22 / 2;
    }

    #C1 .div68 {
        grid-area: 21 / 2 / 22 / 11;
    }

    /* Philhealth */
    #C1 .div69 {
        grid-area: 21 / 11 / 22 / 25;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div70 {
        grid-area: 21 / 25 / 22 / 35;
        justify-content: center;
        position: relative;
        z-index: 1;
        box-shadow: 0 -1px 0 0 #eaeaea;
    }

    /* ZIP CODE Label */

    #C1 .div71 {
        grid-area: 22 / 1 / 23 / 2;
    }

    #C1 .div72 {
        grid-area: 23 / 1 / 24 / 2;
    }

    #C1 .div73 {
        grid-area: 24 / 1 / 25 / 2;
    }

    #C1 .div74 {
        grid-area: 22 / 2 / 23 / 11;
    }

    #C1 .div75 {
        grid-area: 23 / 2 / 24 / 11;
    }

    #C1 .div76 {
        grid-area: 24 / 2 / 25 / 11;
    }

    #C1 .div77 {
        grid-area: 22 / 11 / 23 / 25;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div78 {
        grid-area: 23 / 11 / 24 / 25;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div79 {
        grid-area: 24 / 11 / 25 / 25;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div80 {
        grid-area: 22 / 25 / 23 / 35;
    }

    /* Telephone Label */
    #C1 .div81 {
        grid-area: 23 / 25 / 24 / 35;
    }

    /* Mobile Label */
    #C1 .div82 {
        grid-area: 24 / 25 / 25 / 35;
    }

    /* Email Label */
    #C1 .div83 {
        grid-area: 22 / 35 / 23 / 61;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div84 {
        grid-area: 23 / 35 / 24 / 61;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    #C1 .div85 {
        grid-area: 24 / 35 / 25 / 61;
        font-weight: bold;
        justify-content: center;
        font-size: 10pt;
    }

    /* Family Background */
    #C1 .pds-family-background-section {
        display: grid;
        grid-template-columns: repeat(60, 1fr);
        width: 100%;
        border: 2px solid #000;
        border-top: 2px solid #000;
        border-bottom: none;
        margin-top: 0;
        background-color: #000;
        gap: 1px;
    }

    #C1 :where(.pds-family-background-section)>div {
        border: none;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        min-height: 15px;
        font-family: 'Arial Narrow', sans-serif;
        font-size: 7pt;
        background-color: white;
    }

    #C1 .fb-div1 {
        grid-column: 1 / -1;
        background-color: #969696;
        color: white;
        font-weight: bold;
        font-style: italic;
        font-size: 11pt !important;
        padding-left: 5px;
    }

    #C1 .fb-div2,
    #C1 .fb-div3,
    #C1 .fb-div4,
    #C1 .fb-div13,
    #C1 .fb-div14,
    #C1 .fb-div15,
    #C1 .fb-div16,
    #C1 .fb-div25,
    #C1 .fb-div26,
    #C1 .fb-div27,
    #C1 .fb-div35,
    #C1 .fb-div37,
    #C1 .fb-div38,
    #C1 .fb-div39 {
        justify-content: flex-end;
        padding-right: 2px;
        background-color: #eaeaea;
    }

    /* Spouse */
    #C1 .fb-div2 {
        grid-area: 2 / 1 / 3 / 2;
    }

    #C1 .fb-div5 {
        grid-area: 2 / 2 / 3 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div6 {
        grid-area: 2 / 11 / 3 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    #C1 .fb-div3 {
        grid-area: 3 / 1 / 4 / 2;
    }

    #C1 .fb-div7 {
        grid-area: 3 / 2 / 4 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div8 {
        grid-area: 3 / 11 / 4 / 25;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    #C1 .fb-div9 {
        grid-area: 3 / 25 / 4 / 35;
        background-color: #eaeaea;
        padding-left: 5px;
        justify-content: space-between;
        padding-right: 5px;
    }

    #C1 .fb-div4 {
        grid-area: 4 / 1 / 5 / 2;
    }

    #C1 .fb-div10 {
        grid-area: 4 / 2 / 5 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div11 {
        grid-area: 4 / 11 / 5 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    #C1 .fb-div13 {
        grid-area: 5 / 1 / 6 / 2;
    }

    #C1 .fb-div17 {
        grid-area: 5 / 2 / 6 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div21 {
        grid-area: 5 / 11 / 6 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    #C1 .fb-div14 {
        grid-area: 6 / 1 / 7 / 2;
    }

    #C1 .fb-div18 {
        grid-area: 6 / 2 / 7 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div22 {
        grid-area: 6 / 11 / 7 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    #C1 .fb-div15 {
        grid-area: 7 / 1 / 8 / 2;
    }

    #C1 .fb-div19 {
        grid-area: 7 / 2 / 8 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div23 {
        grid-area: 7 / 11 / 8 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    #C1 .fb-div16 {
        grid-area: 8 / 1 / 9 / 2;
    }

    #C1 .fb-div20 {
        grid-area: 8 / 2 / 9 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div24 {
        grid-area: 8 / 11 / 9 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    /* Father */
    #C1 .fb-div25 {
        grid-area: 9 / 1 / 10 / 2;
    }

    #C1 .fb-div28 {
        grid-area: 9 / 2 / 10 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div31 {
        grid-area: 9 / 11 / 10 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    #C1 .fb-div26 {
        grid-area: 10 / 1 / 11 / 2;
    }

    #C1 .fb-div29 {
        grid-area: 10 / 2 / 11 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div32 {
        grid-area: 10 / 11 / 11 / 25;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    #C1 .fb-div33 {
        grid-area: 10 / 25 / 11 / 35;
        background-color: #eaeaea;
        padding-left: 5px;
        justify-content: space-between;
        padding-right: 5px;
    }

    #C1 .fb-div27 {
        grid-area: 11 / 1 / 12 / 2;
    }

    #C1 .fb-div30 {
        grid-area: 11 / 2 / 12 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div34 {
        grid-area: 11 / 11 / 12 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    /* Mother */
    #C1 .fb-div35 {
        grid-area: 12 / 1 / 13 / 2;
    }

    #C1 .fb-div36 {
        grid-area: 12 / 2 / 13 / 35;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div37 {
        grid-area: 13 / 1 / 14 / 2;
    }

    #C1 .fb-div40 {
        grid-area: 13 / 2 / 14 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div43 {
        grid-area: 13 / 11 / 14 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    #C1 .fb-div38 {
        grid-area: 14 / 1 / 15 / 2;
    }

    #C1 .fb-div41 {
        grid-area: 14 / 2 / 15 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div44 {
        grid-area: 14 / 11 / 15 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    #C1 .fb-div39 {
        grid-area: 15 / 1 / 16 / 2;
    }

    #C1 .fb-div42 {
        grid-area: 15 / 2 / 16 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .fb-div45 {
        grid-area: 15 / 11 / 16 / 35;
        font-weight: bold;
        padding-left: 5px;
        font-size: 10pt;
    }

    /* Children Headers */
    #C1 .fb-div46 {
        grid-area: 2 / 35 / 3 / 51;
        background-color: #eaeaea;
        justify-content: center;
        text-align: center;
    }

    #C1 .fb-div47 {
        grid-area: 2 / 51 / 3 / 61;
        background-color: #eaeaea;
        justify-content: center;
        text-align: center;
    }

    /* Children Rows */
    #C1 .child-name {
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
        padding-left: 2px;
    }

    #C1 .child-dob {
        font-weight: bold;
        font-size: 10pt;
        justify-content: center;
    }

    #C1 .fb-div48 {
        grid-area: 3 / 35 / 4 / 51;
    }

    #C1 .fb-div49 {
        grid-area: 3 / 51 / 4 / 61;
    }

    #C1 .fb-div50 {
        grid-area: 4 / 35 / 5 / 51;
    }

    #C1 .fb-div51 {
        grid-area: 4 / 51 / 5 / 61;
    }

    #C1 .fb-div52 {
        grid-area: 5 / 35 / 6 / 51;
    }

    #C1 .fb-div53 {
        grid-area: 5 / 51 / 6 / 61;
    }

    #C1 .fb-div54 {
        grid-area: 6 / 35 / 7 / 51;
    }

    #C1 .fb-div55 {
        grid-area: 6 / 51 / 7 / 61;
    }

    #C1 .fb-div56 {
        grid-area: 7 / 35 / 8 / 51;
    }

    #C1 .fb-div57 {
        grid-area: 7 / 51 / 8 / 61;
    }

    #C1 .fb-div58 {
        grid-area: 8 / 35 / 9 / 51;
    }

    #C1 .fb-div59 {
        grid-area: 8 / 51 / 9 / 61;
    }

    #C1 .fb-div60 {
        grid-area: 9 / 35 / 10 / 51;
    }

    #C1 .fb-div61 {
        grid-area: 9 / 51 / 10 / 61;
    }

    #C1 .fb-div62 {
        grid-area: 10 / 35 / 11 / 51;
    }

    #C1 .fb-div63 {
        grid-area: 10 / 51 / 11 / 61;
    }

    #C1 .fb-div64 {
        grid-area: 11 / 35 / 12 / 51;
    }

    #C1 .fb-div65 {
        grid-area: 11 / 51 / 12 / 61;
    }

    #C1 .fb-div66 {
        grid-area: 12 / 35 / 13 / 51;
    }

    #C1 .fb-div67 {
        grid-area: 12 / 51 / 13 / 61;
    }

    #C1 .fb-div68 {
        grid-area: 13 / 35 / 14 / 51;
    }

    #C1 .fb-div69 {
        grid-area: 13 / 51 / 14 / 61;
    }

    #C1 .fb-div70 {
        grid-area: 14 / 35 / 15 / 51;
    }

    #C1 .fb-div71 {
        grid-area: 14 / 51 / 15 / 61;
    }

    #C1 .fb-div72 {
        grid-area: 15 / 35 / 16 / 61;
        justify-content: center;
        font-style: italic;
        font-weight: bold;
        color: red;
    }

    /* Educational Background */
    #C1 .pds-educational-background-section {
        display: grid;
        grid-template-columns: repeat(60, 1fr);
        width: 100%;
        border: 2px solid #000;
        border-top: 2px solid #000;
        margin-top: 0;
        background-color: #000;
        gap: 1px;
    }

    #C1 :where(.pds-educational-background-section)>div {
        border: none;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        min-height: 15px;
        font-family: 'Arial Narrow', sans-serif;
        font-size: 7pt;
        background-color: white;
    }

    #C1 .eb-div1 {
        grid-column: 1 / -1;
        background-color: #969696;
        color: white;
        font-weight: bold;
        font-style: italic;
        font-size: 11pt !important;
        padding-left: 5px;
    }

    #C1 .eb-div2,
    #C1 .eb-div3,
    #C1 .eb-div17,
    #C1 .eb-div18,
    #C1 .eb-div19,
    #C1 .eb-div20,
    #C1 .eb-div21 {
        justify-content: flex-end;
        padding-right: 2px;
        background-color: #eaeaea;
    }

    #C1 .eb-div2 {
        grid-area: 2 / 1 / 3 / 2;
    }

    #C1 .eb-div3 {
        grid-area: 3 / 1 / 4 / 2;
    }

    #C1 .eb-div4 {
        grid-area: 2 / 2 / 4 / 11;
        background-color: #eaeaea;
        justify-content: center;
    }

    #C1 .eb-div5 {
        grid-area: 2 / 11 / 4 / 23;
        background-color: #eaeaea;
        justify-content: center;
        text-align: center;
    }

    #C1 .eb-div6 {
        grid-area: 2 / 23 / 4 / 35;
        background-color: #eaeaea;
        justify-content: center;
        text-align: center;
    }

    #C1 .eb-div7 {
        grid-area: 2 / 35 / 3 / 43;
        background-color: #eaeaea;
        justify-content: center;
        text-align: center;
    }

    #C1 .eb-div9 {
        grid-area: 2 / 43 / 4 / 51;
        background-color: #eaeaea;
        justify-content: center;
        text-align: center;
    }

    #C1 .eb-div10 {
        grid-area: 2 / 51 / 4 / 56;
        background-color: #eaeaea;
        justify-content: center;
        text-align: center;
    }

    #C1 .eb-div11 {
        grid-area: 2 / 56 / 4 / 61;
        background-color: #eaeaea;
        justify-content: center;
        text-align: center;
    }

    #C1 .eb-div12 {
        grid-area: 3 / 35 / 4 / 39;
        background-color: #eaeaea;
        justify-content: center;
    }

    #C1 .eb-div13 {
        grid-area: 3 / 39 / 4 / 43;
        background-color: #eaeaea;
        justify-content: center;
    }

    /* Education Rows */
    #C1 .eb-cell {
        justify-content: center;
        text-align: center;
        font-weight: bold;
        font-size: 10pt;
        padding: 0 2px;
    }

    /* Elementary */
    #C1 .eb-div17 {
        grid-area: 4 / 1 / 5 / 2;
    }

    #C1 .eb-div22 {
        grid-area: 4 / 2 / 5 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .eb-div27 {
        grid-area: 4 / 11 / 5 / 23;
    }

    #C1 .eb-div32 {
        grid-area: 4 / 23 / 5 / 35;
    }

    #C1 .eb-div37 {
        grid-area: 4 / 35 / 5 / 39;
    }

    #C1 .eb-div38 {
        grid-area: 4 / 39 / 5 / 43;
    }

    #C1 .eb-div47 {
        grid-area: 4 / 43 / 5 / 51;
    }

    #C1 .eb-div48 {
        grid-area: 4 / 51 / 5 / 56;
    }

    #C1 .eb-div49 {
        grid-area: 4 / 56 / 5 / 61;
    }

    /* Secondary */
    #C1 .eb-div18 {
        grid-area: 5 / 1 / 6 / 2;
    }

    #C1 .eb-div23 {
        grid-area: 5 / 2 / 6 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .eb-div28 {
        grid-area: 5 / 11 / 6 / 23;
    }

    #C1 .eb-div33 {
        grid-area: 5 / 23 / 6 / 35;
    }

    #C1 .eb-div39 {
        grid-area: 5 / 35 / 6 / 39;
    }

    #C1 .eb-div40 {
        grid-area: 5 / 39 / 6 / 43;
    }

    #C1 .eb-div50 {
        grid-area: 5 / 43 / 6 / 51;
    }

    #C1 .eb-div51 {
        grid-area: 5 / 51 / 6 / 56;
    }

    #C1 .eb-div52 {
        grid-area: 5 / 56 / 6 / 61;
    }

    /* Vocational */
    #C1 .eb-div19 {
        grid-area: 6 / 1 / 7 / 2;
    }

    #C1 .eb-div24 {
        grid-area: 6 / 2 / 7 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .eb-div29 {
        grid-area: 6 / 11 / 7 / 23;
    }

    #C1 .eb-div34 {
        grid-area: 6 / 23 / 7 / 35;
    }

    #C1 .eb-div41 {
        grid-area: 6 / 35 / 7 / 39;
    }

    #C1 .eb-div42 {
        grid-area: 6 / 39 / 7 / 43;
    }

    #C1 .eb-div53 {
        grid-area: 6 / 43 / 7 / 51;
    }

    #C1 .eb-div54 {
        grid-area: 6 / 51 / 7 / 56;
    }

    #C1 .eb-div55 {
        grid-area: 6 / 56 / 7 / 61;
    }

    /* College */
    #C1 .eb-div20 {
        grid-area: 7 / 1 / 8 / 2;
    }

    #C1 .eb-div25 {
        grid-area: 7 / 2 / 8 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .eb-div30 {
        grid-area: 7 / 11 / 8 / 23;
    }

    #C1 .eb-div35 {
        grid-area: 7 / 23 / 8 / 35;
    }

    #C1 .eb-div43 {
        grid-area: 7 / 35 / 8 / 39;
    }

    #C1 .eb-div44 {
        grid-area: 7 / 39 / 8 / 43;
    }

    #C1 .eb-div56 {
        grid-area: 7 / 43 / 8 / 51;
    }

    #C1 .eb-div57 {
        grid-area: 7 / 51 / 8 / 56;
    }

    #C1 .eb-div58 {
        grid-area: 7 / 56 / 8 / 61;
    }

    /* Graduate */
    #C1 .eb-div21 {
        grid-area: 8 / 1 / 9 / 2;
    }

    #C1 .eb-div26 {
        grid-area: 8 / 2 / 9 / 11;
        background-color: #eaeaea;
        padding-left: 5px;
    }

    #C1 .eb-div31 {
        grid-area: 8 / 11 / 9 / 23;
    }

    #C1 .eb-div36 {
        grid-area: 8 / 23 / 9 / 35;
    }

    #C1 .eb-div45 {
        grid-area: 8 / 35 / 9 / 39;
    }

    #C1 .eb-div46 {
        grid-area: 8 / 39 / 9 / 43;
    }

    #C1 .eb-div59 {
        grid-area: 8 / 43 / 9 / 51;
    }

    #C1 .eb-div60 {
        grid-area: 8 / 51 / 9 / 56;
    }

    #C1 .eb-div61 {
        grid-area: 8 / 56 / 9 / 61;
    }

    /* Footer */
    #C1 .eb-div62 {
        grid-area: 9 / 1 / 10 / 61;
        background-color: #eaeaea;
        text-align: center;
        justify-content: center;
        font-style: italic;
        font-weight: bold;
        color: red;
    }

    #C1 .eb-div63 {
        grid-area: 10 / 1 / 11 / 13;
        background-color: #eaeaea;
        justify-content: center;
        font-weight: bold;
        font-style: italic;
    }

    #C1 .eb-div64 {
        grid-area: 10 / 13 / 11 / 35;
    }

    #C1 .eb-div65 {
        grid-area: 10 / 35 / 11 / 43;
        background-color: #eaeaea;
        justify-content: center;
        font-weight: bold;
        font-style: italic;
    }

    #C1 .eb-div66 {
        grid-area: 10 / 43 / 11 / 61;
    }
</style>

<div id="C1">
    <div class="pds-wrapper pds-preview-inner">
        <div class="pds-title-section">
            <div class="title-div1"> <span class="form-number">CS Form No. 212</span> <br>
                <span class="form-version">Revised 2025</span>
            </div>
            <div class="title-div2"><span class="form-title">PERSONAL DATA SHEET</span></div>
            <div class="title-div3"> </div>
            <div class="title-div5"></div>
            <div class="title-div6"><span class="warning-text">WARNING: Any misrepresentation made in the Personal Data
                    Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against
                    the person concerned. </span></div>

            <div class="title-div8"><span class="guide-text">READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA
                    SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.</span></div>
            <div class="title-div9"><span class="instruction-text">Print legibly if accomplished through own
                    handwriting. Tick appropriate boxes ( <input type="checkbox" disabled> ) and use separate sheet if
                    necessary. Indicate N/A if not applicable.</span> <span class="instruction-bold">DO NOT
                    ABBREVIATE.</span></div>
        </div>

        <!-- Personal Information Section -->
        <div class="pds-personal-info-section">
            <div class="info-div1">I. PERSONAL INFORMATION</div>
            <div class="info-num1"><span class="personal-info-text">1.</span></div>
            <div class="info-div2"><span class="personal-info-text">SURNAME</span></div>
            <div class="info-num2"><span class="personal-info-text">2.</span></div>
            <div class="info-div3"><span class="personal-info-text">FIRST NAME</span></div>
            <div class="info-num3"><span class="personal-info-text"></span></div>
            <div class="info-div4"><span class="personal-info-text">MIDDLE NAME</span></div>

            <div class="info-div5">{{ $pds->surname ?? 'N/A' }}</div>
            <div class="info-div6">{{ $pds->middle_name ?? 'N/A' }}</div>
            <div class="info-div7">
                <span class="personal-info-extension">NAME EXTENSION (JR., SR)</span>
                <b style="font-size: 10pt;">{{ $pds->name_extension ?? '' }}</b>
            </div>
            <div class="info-div8">{{ $pds->first_name ?? 'N/A' }}</div>

            <!-- Merged Extension Items -->
            <div class="div1">3.</div>
            <div class="div2">DATE OF BIRTH <BR> (dd/mm/yyyy)</div>
            <div class="div4">
                @if(!empty($pds->dob))
                    {{ \Carbon\Carbon::parse($pds->dob)->format('d/m/Y') }}
                @endif
            </div>
            <div class="div5">4.</div>
            <div class="div7">PLACE OF BIRTH</div>
            <div class="div8">{{ $pds->pob ?? 'N/A' }}</div>
            <div class="div9">5.</div>
            <div class="div10">SEX AT BIRTH</div>
            <div class="div11">
                <div class="checkbox-container">
                    <div class="checkbox-item"><input type="checkbox" {{ ($pds->sex == 'Male') ? 'checked' : '' }}> Male
                    </div>
                    <div class="checkbox-item"><input type="checkbox" {{ ($pds->sex == 'Female') ? 'checked' : '' }}>
                        Female</div>
                </div>
            </div>
            <div class="div12">
                <div style="width: 100%; text-align: left;">16. CITIZENSHIP</div>
                <div style="width: 100%; text-align: center; font-size: 6pt; margin-top: 5px;">If holder of dual
                    citizenship,<br>please indicate the details.</div>
            </div>
            <div class="div13">
                <div class="checkbox-container" style="align-items: flex-start; flex-direction: row;">
                    <div class="checkbox-item"><input type="checkbox" {{ ($pds->citizenship_type == 'Filipino') ? 'checked' : '' }}> Filipino</div>
                    <div class="checkbox-col">
                        <div class="checkbox-item"><input type="checkbox" {{ ($pds->citizenship_type == 'Dual Citizenship') ? 'checked' : '' }}> Dual Citizenship</div>
                        <div class="checkbox-item" style="margin-left: 15px;"><input type="checkbox" {{ ($pds->citizenship_nature == 'by birth') ? 'checked' : '' }}> by birth</div>
                        <div class="checkbox-item" style="margin-left: 15px;"><input type="checkbox" {{ ($pds->citizenship_nature == 'by naturalization') ? 'checked' : '' }}> by naturalization
                        </div>
                        <div class="checkbox-item" style="margin-left: 15px;">Pls. indicate country:</div>
                    </div>
                </div>
            </div>
            <div class="div14">{{ $pds->citizenship_country ?? '' }}</div>

            <!-- Additional Extension Section -->
            <div class="div17">6.</div>
            <div class="div20">CIVIL STATUS</div>
            <div class="div23">
                <div class="checkbox-container" style="display: grid; grid-template-columns: 1fr 1fr;">
                    <div class="checkbox-item"><input type="checkbox" {{ ($pds->civil_status == 'Single') ? 'checked' : '' }}> Single</div>
                    <div class="checkbox-item"><input type="checkbox" {{ ($pds->civil_status == 'Married') ? 'checked' : '' }}> Married</div>
                    <div class="checkbox-item"><input type="checkbox" {{ ($pds->civil_status == 'Widowed') ? 'checked' : '' }}> Widowed</div>
                    <div class="checkbox-item"><input type="checkbox" {{ ($pds->civil_status == 'Separated') ? 'checked' : '' }}> Separated</div>
                    <div class="checkbox-item" style="grid-column: 1 / -1;"><input type="checkbox" {{ ($pds->civil_status == 'Other') ? 'checked' : '' }}> Other/s: <span
                            style="border-bottom: 1px solid black; width: 100px; margin-left: 2px; display: inline-block;"></span>
                    </div>
                </div>
            </div>
            <div class="div26">17. RESIDENTIAL ADDRESS</div>

            <div class="div29">House/Block/Lot No.</div>
            <div class="div30">Street</div>

            <div class="div40">{{ $pds->res_house_block_lot ?? '' }}</div>
            <div class="div41">{{ $pds->res_street ?? '' }}</div>

            <div class="div33">Subdivision/Village</div>
            <div class="div34">Barangay</div>

            <div class="div31">{{ $pds->res_subdivision_village ?? '' }}</div>
            <div class="div32">{{ $pds->res_barangay ?? '' }}</div>

            <div class="div37">City/Municipality</div>
            <div class="div38">Province</div>

            <div class="div18">7.</div>
            <div class="div21">HEIGHT (m)</div>
            <div class="div24">{{ $pds->height ?? '' }}</div>

            <div class="div35">{{ $pds->res_city_municipality ?? '' }}</div>
            <div class="div36">{{ $pds->res_province ?? '' }}</div>

            <div class="div19">8.</div>
            <div class="div22">WEIGHT (kg)</div>
            <div class="div25">{{ $pds->weight ?? '' }}</div>
            <div class="div28">ZIP CODE</div>
            <div class="div39">{{ $pds->res_zip_code ?? '' }}</div>

            <div class="div42">9.</div>
            <div class="div43">10.</div>
            <div class="div44">11.</div>
            <div class="div45">BLOOD TYPE</div>
            <div class="div46">UMID ID NO.</div>
            <div class="div47">PAG-IBIG ID NO.</div>
            <div class="div48">{{ $pds->blood_type ?? '' }}</div>
            <div class="div49">{{ $pds->gsis_id ?? '' }}</div>
            <div class="div50">{{ $pds->pagibig_no ?? '' }}</div>
            <div class="div51">18. PERMANENT ADDRESS</div>
            <div class="div54">{{ $pds->perm_house_block_lot ?? '' }}</div>
            <div class="div55">{{ $pds->perm_street ?? '' }}</div>
            <div class="div56">House/Block/Lot No.</div>
            <div class="div57">Street</div>
            <div class="div58">{{ $pds->perm_subdivision_village ?? '' }}</div>
            <div class="div59">{{ $pds->perm_barangay ?? '' }}</div>
            <div class="div60">Subdivision/Village</div>
            <div class="div61">Barangay</div>
            <div class="div62">{{ $pds->perm_city_municipality ?? '' }}</div>
            <div class="div63">{{ $pds->perm_province ?? '' }}</div>
            <div class="div64">City/Municipality</div>
            <div class="div65">Province</div>
            <div class="div66">{{ $pds->perm_zip_code ?? '' }}</div>
            <div class="div67">12.</div>
            <div class="div68">PHILHEALTH NO.</div>
            <div class="div69">{{ $pds->philhealth_no ?? '' }}</div>
            <div class="div70">ZIP CODE</div>
            <div class="div71">13.</div>
            <div class="div72">14.</div>
            <div class="div73">15.</div>
            <div class="div74">PhilSys Number (PSN):</div>
            <div class="div75">TIN NO.</div>
            <div class="div76">AGENCY EMPLOYEE NO.</div>
            <div class="div77">{{ $pds->philsys_no ?? '' }}</div>
            <div class="div78">{{ $pds->tin_no ?? '' }}</div>
            <div class="div79">{{ $pds->agency_employee_no ?? '' }}</div>
            <div class="div80">19. TELEPHONE NO.</div>
            <div class="div81">20. MOBILE NO.</div>
            <div class="div82">21. EMAIL ADDRESS (if any)</div>
            <div class="div83">{{ $pds->phone ?? '' }}</div>
            <div class="div84">{{ $pds->mobile ?? '' }}</div>
            <div class="div85">{{ $pds->email ?? '' }}</div>

        </div>

        <!-- Family Background Section -->
        <div class="pds-family-background-section">
            <div class="fb-div1">II. FAMILY BACKGROUND</div>
            <div class="fb-div2">22.</div>
            <div class="fb-div3"></div>
            <div class="fb-div4"></div>
            <div class="fb-div5">SPOUSE'S SURNAME</div>
            <div class="fb-div6">{{ $family->spouse_surname ?? 'N/A' }}</div>
            <div class="fb-div7">FIRST NAME</div>
            <div class="fb-div8">{{ $family->spouse_firstname ?? 'N/A' }}</div>
            <div class="fb-div9">
                NAME EXTENSION (JR, SR)
                <b style="font-size: 10pt;">{{ $family->spouse_suffix ?? '' }}</b>
            </div>

            <div class="fb-div10">MIDDLE NAME</div>
            <div class="fb-div11">{{ $family->spouse_middlename ?? 'N/A' }}</div>
            <div class="fb-div13"></div>
            <div class="fb-div14"></div>
            <div class="fb-div15"></div>
            <div class="fb-div16"></div>
            <div class="fb-div17">OCCUPATION</div>
            <div class="fb-div18">EMPLOYER/BUSINESS NAME</div>
            <div class="fb-div19">BUSINESS ADDRESS</div>
            <div class="fb-div20">TELEPHONE NO.</div>
            <div class="fb-div21">{{ $family->spouse_occupation ?? 'N/A' }}</div>
            <div class="fb-div22">{{ $family->spouse_employer_business ?? 'N/A' }}</div>
            <div class="fb-div23">{{ $family->spouse_employer_business_address ?? 'N/A' }}</div>
            <div class="fb-div24">{{ $family->spouse_telephone_no ?? 'N/A' }}</div>
            <div class="fb-div25">24.</div>
            <div class="fb-div26"></div>
            <div class="fb-div27"></div>
            <div class="fb-div28">FATHER'S SURNAME</div>
            <div class="fb-div29">FIRST NAME</div>
            <div class="fb-div30">MIDDLE NAME</div>
            <div class="fb-div31">{{ $family->father_lastname ?? 'N/A' }}</div>
            <div class="fb-div32">{{ $family->father_firstname ?? 'N/A' }}</div>
            <div class="fb-div33">
                NAME EXTENSION (JR, SR)
                <b style="font-size: 10pt;">{{ $family->father_suffix ?? '' }}</b>
            </div>

            <div class="fb-div34">{{ $family->father_middlename ?? 'N/A' }}</div>
            <div class="fb-div35">25.</div>
            <div class="fb-div36">MOTHER'S MAIDEN NAME</div>
            <div class="fb-div37"></div>
            <div class="fb-div38"></div>
            <div class="fb-div39"></div>
            <div class="fb-div40">SURNAME</div>
            <div class="fb-div41">FIRST NAME</div>
            <div class="fb-div42">MIDDLE NAME</div>
            <div class="fb-div43">{{ $family->mother_lastname ?? 'N/A' }}</div>
            <div class="fb-div44">{{ $family->mother_firstname ?? 'N/A' }}</div>
            <div class="fb-div45">{{ $family->mother_middlename ?? 'N/A' }}</div>
            <div class="fb-div46">23. NAME of CHILDREN (Write full name and list all)</div>
            <div class="fb-div47">DATE OF BIRTH (dd/mm/yyyy)</div>

            <!-- Children List -->
            <div class="fb-div48 child-name">{{ isset($children[0]) ? $children[0]->child_name : '' }}</div>
            <div class="fb-div49 child-dob">
                {{ isset($children[0]) && $children[0]->date_of_birth ? \Carbon\Carbon::parse($children[0]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div50 child-name">{{ isset($children[1]) ? $children[1]->child_name : '' }}</div>
            <div class="fb-div51 child-dob">
                {{ isset($children[1]) && $children[1]->date_of_birth ? \Carbon\Carbon::parse($children[1]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div52 child-name">{{ isset($children[2]) ? $children[2]->child_name : '' }}</div>
            <div class="fb-div53 child-dob">
                {{ isset($children[2]) && $children[2]->date_of_birth ? \Carbon\Carbon::parse($children[2]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div54 child-name">{{ isset($children[3]) ? $children[3]->child_name : '' }}</div>
            <div class="fb-div55 child-dob">
                {{ isset($children[3]) && $children[3]->date_of_birth ? \Carbon\Carbon::parse($children[3]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div56 child-name">{{ isset($children[4]) ? $children[4]->child_name : '' }}</div>
            <div class="fb-div57 child-dob">
                {{ isset($children[4]) && $children[4]->date_of_birth ? \Carbon\Carbon::parse($children[4]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div58 child-name">{{ isset($children[5]) ? $children[5]->child_name : '' }}</div>
            <div class="fb-div59 child-dob">
                {{ isset($children[5]) && $children[5]->date_of_birth ? \Carbon\Carbon::parse($children[5]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div60 child-name">{{ isset($children[6]) ? $children[6]->child_name : '' }}</div>
            <div class="fb-div61 child-dob">
                {{ isset($children[6]) && $children[6]->date_of_birth ? \Carbon\Carbon::parse($children[6]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div62 child-name">{{ isset($children[7]) ? $children[7]->child_name : '' }}</div>
            <div class="fb-div63 child-dob">
                {{ isset($children[7]) && $children[7]->date_of_birth ? \Carbon\Carbon::parse($children[7]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div64 child-name">{{ isset($children[8]) ? $children[8]->child_name : '' }}</div>
            <div class="fb-div65 child-dob">
                {{ isset($children[8]) && $children[8]->date_of_birth ? \Carbon\Carbon::parse($children[8]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div66 child-name">{{ isset($children[9]) ? $children[9]->child_name : '' }}</div>
            <div class="fb-div67 child-dob">
                {{ isset($children[9]) && $children[9]->date_of_birth ? \Carbon\Carbon::parse($children[9]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div68 child-name">{{ isset($children[10]) ? $children[10]->child_name : '' }}</div>
            <div class="fb-div69 child-dob">
                {{ isset($children[10]) && $children[10]->date_of_birth ? \Carbon\Carbon::parse($children[10]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div70 child-name">{{ isset($children[11]) ? $children[11]->child_name : '' }}</div>
            <div class="fb-div71 child-dob">
                {{ isset($children[11]) && $children[11]->date_of_birth ? \Carbon\Carbon::parse($children[11]->date_of_birth)->format('d/m/Y') : '' }}
            </div>

            <div class="fb-div72">(Continue on separate sheet if necessary)</div>
        </div>

        <!-- Educational Background Section -->
        <div class="pds-educational-background-section">
            <div class="eb-div1">III. EDUCATIONAL BACKGROUND</div>
            <div class="eb-div2">26.</div>
            <div class="eb-div3"></div>
            <div class="eb-div4">LEVEL</div>
            <div class="eb-div5">NAME OF SCHOOL<br>(Write in full)</div>
            <div class="eb-div6">BASIC EDUCATION/DEGREE/COURSE<br>(Write in full)</div>
            <div class="eb-div7">PERIOD OF ATTENDANCE</div>
            <div class="eb-div9">HIGHEST LEVEL/UNITS EARNED<br>(if not graduated)</div>
            <div class="eb-div10">YEAR GRADUATED</div>
            <div class="eb-div11">SCHOLARSHIP/ ACADEMIC HONORS RECEIVED</div>

            <div class="eb-div12">From</div>
            <div class="eb-div13">To</div>

            @php
                $elem = collect($education)->firstWhere('educational_level', 'ELEMENTARY');
                $sec = collect($education)->firstWhere('educational_level', 'SECONDARY');
                $voc = collect($education)->firstWhere('educational_level', 'VOCATIONAL/TRADE COURSE');
                $coll = collect($education)->firstWhere('educational_level', 'COLLEGE');
                $grad = collect($education)->firstWhere('educational_level', 'GRADUATE STUDIES');
            @endphp

            <!-- Elementary -->
            <div class="eb-div17"></div>
            <div class="eb-div22">ELEMENTARY</div>
            <div class="eb-div27 eb-cell">{{ optional($elem)->school ?? 'N/A' }}</div>
            <div class="eb-div32 eb-cell">{{ optional($elem)->degree ?? 'N/A' }}</div>
            <div class="eb-div37 eb-cell">{{ optional($elem)->year_from ?? 'N/A' }}</div>
            <div class="eb-div38 eb-cell">{{ optional($elem)->year_to ?? 'N/A' }}</div>
            <div class="eb-div47 eb-cell">{{ optional($elem)->highest_level ?? 'N/A' }}</div>
            <div class="eb-div48 eb-cell">{{ optional($elem)->year_graduated ?? 'N/A' }}</div>
            <div class="eb-div49 eb-cell">{{ optional($elem)->scholarship ?? 'N/A' }}</div>

            <!-- Secondary -->
            <div class="eb-div18"></div>
            <div class="eb-div23">SECONDARY</div>
            <div class="eb-div28 eb-cell">{{ optional($sec)->school ?? 'N/A' }}</div>
            <div class="eb-div33 eb-cell">{{ optional($sec)->degree ?? 'N/A' }}</div>
            <div class="eb-div39 eb-cell">{{ optional($sec)->year_from ?? 'N/A' }}</div>
            <div class="eb-div40 eb-cell">{{ optional($sec)->year_to ?? 'N/A' }}</div>
            <div class="eb-div50 eb-cell">{{ optional($sec)->highest_level ?? 'N/A' }}</div>
            <div class="eb-div51 eb-cell">{{ optional($sec)->year_graduated ?? 'N/A' }}</div>
            <div class="eb-div52 eb-cell">{{ optional($sec)->scholarship ?? 'N/A' }}</div>

            <!-- Vocational -->
            <div class="eb-div19"></div>
            <div class="eb-div24">VOCATIONAL /<BR>TRADE COURSE</div>
            <div class="eb-div29 eb-cell">{{ optional($voc)->school ?? 'N/A' }}</div>
            <div class="eb-div34 eb-cell">{{ optional($voc)->degree ?? 'N/A' }}</div>
            <div class="eb-div41 eb-cell">{{ optional($voc)->year_from ?? 'N/A' }}</div>
            <div class="eb-div42 eb-cell">{{ optional($voc)->year_to ?? 'N/A' }}</div>
            <div class="eb-div53 eb-cell">{{ optional($voc)->highest_level ?? 'N/A' }}</div>
            <div class="eb-div54 eb-cell">{{ optional($voc)->year_graduated ?? 'N/A' }}</div>
            <div class="eb-div55 eb-cell">{{ optional($voc)->scholarship ?? 'N/A' }}</div>

            <!-- College -->
            <div class="eb-div20"></div>
            <div class="eb-div25">COLLEGE</div>
            <div class="eb-div30 eb-cell">{{ optional($coll)->school ?? 'N/A' }}</div>
            <div class="eb-div35 eb-cell">{{ optional($coll)->degree ?? 'N/A' }}</div>
            <div class="eb-div43 eb-cell">{{ optional($coll)->year_from ?? 'N/A' }}</div>
            <div class="eb-div44 eb-cell">{{ optional($coll)->year_to ?? 'N/A' }}</div>
            <div class="eb-div56 eb-cell">{{ optional($coll)->highest_level ?? 'N/A' }}</div>
            <div class="eb-div57 eb-cell">{{ optional($coll)->year_graduated ?? 'N/A' }}</div>
            <div class="eb-div58 eb-cell">{{ optional($coll)->scholarship ?? 'N/A' }}</div>

            <!-- Graduate Studies -->
            <div class="eb-div21"></div>
            <div class="eb-div26">GRADUATE STUDIES</div>
            <div class="eb-div31 eb-cell">{{ optional($grad)->school ?? 'N/A' }}</div>
            <div class="eb-div36 eb-cell">{{ optional($grad)->degree ?? 'N/A' }}</div>
            <div class="eb-div45 eb-cell">{{ optional($grad)->year_from ?? 'N/A' }}</div>
            <div class="eb-div46 eb-cell">{{ optional($grad)->year_to ?? 'N/A' }}</div>
            <div class="eb-div59 eb-cell">{{ optional($grad)->highest_level ?? 'N/A' }}</div>
            <div class="eb-div60 eb-cell">{{ optional($grad)->year_graduated ?? 'N/A' }}</div>
            <div class="eb-div61 eb-cell">{{ optional($grad)->scholarship ?? 'N/A' }}</div>

            <div class="eb-div62">(Continue on separate sheet if necessary)</div>

            <div class="eb-div63">SIGNATURE</div>
            <div class="eb-div64"></div>
            <div class="eb-div65">DATE</div>
            <div class="eb-div66"></div>
        </div>
        <div style="font-family: 'Arial Narrow', sans-serif; font-size: 9px; text-align: right; padding: 5px 0;">
            <b><i>CS FORM 212 (Revised 2025), Page 1 of 4</i></b>
        </div>
    </div>
</div>