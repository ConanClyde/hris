<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CS Form 212 (Revised 2017)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            margin: 0;
            padding: 0;
        }
        @page {
            margin: 1cm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px;
            vertical-align: top;
        }
        .header {
            text-align: center;
            font-weight: bold;
            font-style: italic;
            font-size: 9pt;
            border: none;
        }
        .title {
            text-align: center;
            font-weight: 900;
            font-size: 16pt;
            margin: 5px 0;
        }
        .section-header {
            background-color: #969696;
            color: white;
            font-weight: bold;
            font-style: italic;
            padding: 2px 5px;
            margin-top: -1px;
            border: 1px solid #000;
        }
        .field-label {
            background-color: #eaeaea;
            font-size: 7.5pt;
        }
        .field-value {
            text-transform: uppercase;
        }
        .checkbox-box {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            margin-right: 2px;
            position: relative;
            top: 2px;
        }
        .checked::after {
            content: 'x';
            position: absolute;
            font-size: 10px;
            line-height: 8px;
            left: 2px;
            top: 0;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>

    <!-- Header -->
    <div style="font-size: 7pt; font-style: italic; text-align: left; margin-bottom:-10px;">CS Form No. 212<br>Revised 2017</div>
    <div class="title">PERSONAL DATA SHEET</div>
    <div style="font-size: 7pt; font-weight: bold; font-style: italic; margin-bottom: 5px;">
        WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the person concerned.
    </div>

    @php
        $personal = $pds->personal;
        $family = $pds->family;
    @endphp

    <!-- I. PERSONAL INFORMATION -->
    <div class="section-header">I. PERSONAL INFORMATION</div>
    <table>
        <tr>
            <td class="field-label" width="15%">2. SURNAME</td>
            <td class="field-value" colspan="3">{{ $personal->surname }}</td>
        </tr>
        <tr>
            <td class="field-label">FIRST NAME</td>
            <td class="field-value" colspan="2">{{ $personal->first_name }}</td>
            <td class="field-label" width="20%">NAME EXTENSION (JR., SR) <span class="field-value bold" style="background:#fff; padding:0 5px; border:1px solid #000;">{{ $personal->name_extension }}</span></td>
        </tr>
        <tr>
            <td class="field-label">MIDDLE NAME</td>
            <td class="field-value" colspan="3">{{ $personal->middle_name }}</td>
        </tr>
        <tr>
            <td class="field-label" rowspan="3">3. DATE OF BIRTH<br>(mm/dd/yyyy)</td>
            <td class="field-value" rowspan="3" width="25%">{{ $personal->date_of_birth?->format('m/d/Y') }}</td>
            <td class="field-label" colspan="2">16. CITIZENSHIP</td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom:none;">
                <span class="checkbox-box {{ $personal->citizenship === 'Filipino' ? 'checked' : '' }}"></span> Filipino &nbsp;&nbsp;&nbsp;
                <span class="checkbox-box {{ $personal->citizenship === 'Dual Citizenship' ? 'checked' : '' }}"></span> Dual Citizenship
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-top:none;">
                If holder of dual citizenship, please indicate the details.
            </td>
        </tr>
        <tr>
            <td class="field-label">4. PLACE OF BIRTH</td>
            <td class="field-value">{{ $personal->place_of_birth }}</td>
            <td class="field-label" width="15%">17. RESIDENTIAL ADDRESS</td>
            <td class="field-value">{{ implode(', ', array_filter([$personal->res_house_block_lot, $personal->res_street, $personal->res_subdivision, $personal->res_barangay, $personal->res_city_municipality, $personal->res_province])) }}</td>
        </tr>
        <tr>
            <td class="field-label">5. SEX</td>
            <td class="field-value">
                <span class="checkbox-box {{ $personal->sex === 'Male' ? 'checked' : '' }}"></span> Male &nbsp;&nbsp;&nbsp;
                <span class="checkbox-box {{ $personal->sex === 'Female' ? 'checked' : '' }}"></span> Female
            </td>
            <td class="field-label">ZIP CODE</td>
            <td class="field-value">{{ $personal->res_zip_code }}</td>
        </tr>
        <tr>
            <td class="field-label">6. CIVIL STATUS</td>
            <td class="field-value">
                <span class="checkbox-box {{ $personal->civil_status === 'Single' ? 'checked' : '' }}"></span> Single &nbsp;&nbsp;
                <span class="checkbox-box {{ $personal->civil_status === 'Married' ? 'checked' : '' }}"></span> Married &nbsp;&nbsp;
                <span class="checkbox-box {{ $personal->civil_status === 'Widowed' ? 'checked' : '' }}"></span> Widowed<br>
                <span class="checkbox-box {{ $personal->civil_status === 'Separated' ? 'checked' : '' }}"></span> Separated &nbsp;&nbsp;
                <span class="checkbox-box {{ $personal->civil_status === 'Other/s' ? 'checked' : '' }}"></span> Other/s
            </td>
            <td class="field-label">18. PERMANENT ADDRESS</td>
            <td class="field-value">{{ implode(', ', array_filter([$personal->perm_house_block_lot, $personal->perm_street, $personal->perm_subdivision, $personal->perm_barangay, $personal->perm_city_municipality, $personal->perm_province])) }}</td>
        </tr>
        <tr>
            <td class="field-label">7. HEIGHT (m)</td>
            <td class="field-value">{{ $personal->height }}</td>
            <td class="field-label">ZIP CODE</td>
            <td class="field-value">{{ $personal->perm_zip_code }}</td>
        </tr>
        <tr>
            <td class="field-label">8. WEIGHT (kg)</td>
            <td class="field-value">{{ $personal->weight }}</td>
            <td class="field-label">19. TELEPHONE NO.</td>
            <td class="field-value">{{ $personal->telephone_no }}</td>
        </tr>
         <tr>
            <td class="field-label">9. BLOOD TYPE</td>
            <td class="field-value">{{ $personal->blood_type }}</td>
            <td class="field-label">20. MOBILE NO.</td>
            <td class="field-value">{{ $personal->mobile_no }}</td>
        </tr>
        <tr>
            <td class="field-label">10. GSIS ID NO.</td>
            <td class="field-value">{{ $personal->gsis_id_no }}</td>
            <td class="field-label">21. EMAIL ADDRESS</td>
            <td class="field-value">{{ $personal->email_address }}</td>
        </tr>
        <tr>
            <td class="field-label">11. PAG-IBIG ID NO.</td>
            <td class="field-value">{{ $personal->pagibig_id_no }}</td>
            <td colspan="2" rowspan="5" style="border:none; border-right: 1px solid #000;">
                <!-- Space for signature and thumbmark would go here -->
            </td>
        </tr>
        <tr>
            <td class="field-label">12. PHILHEALTH NO.</td>
            <td class="field-value">{{ $personal->philhealth_no }}</td>
        </tr>
        <tr>
            <td class="field-label">13. SSS NO.</td>
            <td class="field-value">{{ $personal->sss_no }}</td>
        </tr>
        <tr>
            <td class="field-label">14. TIN NO.</td>
            <td class="field-value">{{ $personal->tin_no }}</td>
        </tr>
        <tr>
            <td class="field-label">15. AGENCY EMPLOYEE NO.</td>
            <td class="field-value">{{ $personal->agency_employee_no }}</td>
        </tr>
    </table>

    <br>
    <div style="text-align:center; font-style:italic;">Page 1 of 4</div>
</body>
</html>
