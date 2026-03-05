<div id="C4">
    <div class="pds-preview-inner table-responsive">
        <form action="">
            <table id="pds-table" style="border: 2px solid #000;">
                {{-- QUESTIONS 34-40 --}}
                <tbody class="table-body question-block">
                    {{-- Q34 --}}
                    <tr>
                        <td colspan="8" class="s-label align-top border-bottom-0" style="width: 65%; font-size: 7.5pt; border-right: 1px solid #000;">
                            <span class="count">34.</span> Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office, or to the person who has immediate supervision over you in the Office, Bureau, or Department where you will be appointed,
                        </td>
                        <td colspan="4" class="border-bottom-0" style="width: 35%;"></td>
                    </tr>
                    <tr>
                        <td colspan="8" class="s-label align-top border-top-0 border-bottom-0" style="font-size: 7.5pt; border-right: 1px solid #000;">
                            a. within the third degree?
                        </td>
                        <td colspan="4" class="align-top border-top-0 border-bottom-0" style="padding: 5px 10px;">
                            <div class="checkbox-group" style="gap: 40px;">
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_1 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_1 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="s-label align-top border-top-0 border-bottom-0" style="font-size: 7.5pt; border-right: 1px solid #000;">
                            b. within the fourth degree (for Local Government Unit - Career Employees)?
                        </td>
                        <td colspan="4" class="align-top border-top-0 border-bottom-0" style="padding: 5px 10px;">
                            <div class="checkbox-group" style="gap: 40px;">
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_2 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_2 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="s-label align-top border-top-0" style="font-size: 7.5pt;"></td>
                        <td colspan="4" class="align-top border-top-0" style="padding: 2px 10px 5px;">
                            <div style="font-size: 7.5pt;">If YES, give details:</div>
                            <input type="text" value="{{ $check->txtarea2 ?? '' }}" style="width: 100%; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                        </td>
                    </tr>

                    {{-- Q35 --}}
                    <tr>
                        <td colspan="8" class="s-label align-top border-bottom-0" style="font-size: 7.5pt;">
                            <span class="count">35.</span> a. Have you ever been found guilty of any administrative offense?
                        </td>
                        <td colspan="4" class="align-top border-bottom-0" style="padding: 5px 10px;">
                            <div class="checkbox-group" style="gap: 40px;">
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_3 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_3 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                            </div>
                            <div style="font-size: 7.5pt; margin-top: 5px;">If YES, give details:</div>
                            <input type="text" value="{{ $check->txtarea3 ?? '' }}" style="width: 100%; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="s-label align-top border-top-0" style="font-size: 7.5pt;">
                            <br>b. Have you been criminally charged before any court?
                        </td>
                        <td colspan="4" class="align-top border-top-0" style="padding: 5px 10px;">
                            <div class="checkbox-group" style="gap: 40px;">
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_4 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_4 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                            </div>
                            <div style="font-size: 7.5pt; margin-top: 5px;">If YES, give details:</div>
                            <div style="display: flex; align-items: flex-end; gap: 5px;">
                                <span style="font-size: 7.5pt; white-space: nowrap;">Date Filed:</span>
                                <input type="text" value="{{ !empty($check->case_date) ? \Carbon\Carbon::parse($check->case_date)->format('d/m/Y') : '' }}" style="flex: 1; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                            </div>
                            <div style="display: flex; align-items: flex-end; gap: 5px; margin-top: 2px;">
                                <span style="font-size: 7.5pt; white-space: nowrap;">Status of Case/s:</span>
                                <input type="text" value="{{ $check->case_status ?? '' }}" style="flex: 1; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                            </div>
                        </td>
                    </tr>

                    {{-- Q36 --}}
                    <tr>
                        <td colspan="8" class="s-label align-top" style="font-size: 7.5pt;">
                            <span class="count">36.</span> Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?
                        </td>
                        <td colspan="4" class="align-top" style="padding: 5px 10px;">
                            <div class="checkbox-group" style="gap: 40px;">
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_5 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_5 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                            </div>
                            <div style="font-size: 7.5pt; margin-top: 5px;">If YES, give details:</div>
                            <input type="text" value="{{ $check->txtarea5 ?? '' }}" style="width: 100%; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                        </td>
                    </tr>

                    {{-- Q37 --}}
                    <tr>
                        <td colspan="8" class="s-label align-top" style="font-size: 7.5pt;">
                            <span class="count">37.</span> Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?
                        </td>
                        <td colspan="4" class="align-top" style="padding: 5px 10px;">
                            <div class="checkbox-group" style="gap: 40px;">
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_6 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_6 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                            </div>
                            <div style="font-size: 7.5pt; margin-top: 5px;">If YES, give details:</div>
                            <input type="text" value="{{ $check->txtarea6 ?? '' }}" style="width: 100%; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                        </td>
                    </tr>

                    {{-- Q38 --}}
                    <tr>
                        <td colspan="8" class="s-label align-top border-bottom-0" style="font-size: 7.5pt;">
                            <span class="count">38.</span> a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?
                        </td>
                        <td colspan="4" class="align-top border-bottom-0" style="padding: 5px 10px;">
                            <div class="checkbox-group" style="gap: 40px;">
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_7 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_7 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                            </div>
                            <div style="font-size: 7.5pt; margin-top: 5px;">If YES, give details:</div>
                            <input type="text" value="{{ $check->txtarea7 ?? '' }}" style="width: 100%; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="s-label align-top border-top-0" style="font-size: 7.5pt;">
                            b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?
                        </td>
                        <td colspan="4" class="align-top border-top-0" style="padding: 5px 10px;">
                            <div class="checkbox-group" style="gap: 40px;">
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_8 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_8 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                            </div>
                            <div style="font-size: 7.5pt; margin-top: 5px;">If YES, give details:</div>
                            <input type="text" value="{{ $check->txtarea8 ?? '' }}" style="width: 100%; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                        </td>
                    </tr>

                    {{-- Q39 --}}
                    <tr>
                        <td colspan="8" class="s-label align-top" style="font-size: 7.5pt;">
                            <span class="count">39.</span> Have you acquired the status of an immigrant or permanent resident of another country?
                        </td>
                        <td colspan="4" class="align-top" style="padding: 5px 10px;">
                            <div class="checkbox-group" style="gap: 40px;">
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_9 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                <label class="option"><input type="checkbox" {{ ($check->cs_id_9 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                            </div>
                            <div style="font-size: 7.5pt; margin-top: 5px;">If YES, give details (country):</div>
                            <input type="text" value="{{ $check->txtarea9 ?? '' }}" style="width: 100%; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                        </td>
                    </tr>

                    {{-- Q40 --}}
                    <tr>
                        <td colspan="8" class="s-label align-top" style="font-size: 7.5pt;">
                            <span class="count">40.</span> Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277, as amended); and (c) Expanded Solo Parents Welfare Act (RA 11861), please answer the following items:
                        </td>
                        <td colspan="4" class="align-top" style="padding: 5px 10px;">
                            <div style="margin-top: 10px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 7.5pt;">a. Are you a member of any indigenous group?</span>
                                    <div class="checkbox-group" style="width: auto; gap: 20px;">
                                        <label class="option"><input type="checkbox" {{ ($check->cs_id_10 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                        <label class="option"><input type="checkbox" {{ ($check->cs_id_10 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: flex-end; gap: 5px; margin-top: 2px;">
                                    <span style="font-size: 7.5pt; white-space: nowrap;">If YES, please specify:</span>
                                    <input type="text" value="{{ $check->txtarea10 ?? '' }}" style="flex: 1; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                                </div>
                            </div>

                            <div style="margin-top: 8px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 7.5pt;">b. Are you a person with disability?</span>
                                    <div class="checkbox-group" style="width: auto; gap: 20px;">
                                        <label class="option"><input type="checkbox" {{ ($check->cs_id_11 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                        <label class="option"><input type="checkbox" {{ ($check->cs_id_11 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: flex-end; gap: 5px; margin-top: 2px;">
                                    <span style="font-size: 7.5pt; white-space: nowrap;">If YES, please specify ID No:</span>
                                    <input type="text" value="{{ $check->txtarea11 ?? '' }}" style="flex: 1; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                                </div>
                            </div>

                            <div style="margin-top: 8px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 7.5pt;">c. Are you a solo parent?</span>
                                    <div class="checkbox-group" style="width: auto; gap: 20px;">
                                        <label class="option"><input type="checkbox" {{ ($check->cs_id_12 ?? '') == 'yes' ? 'checked' : '' }}> YES</label>
                                        <label class="option"><input type="checkbox" {{ ($check->cs_id_12 ?? '') == 'no' ? 'checked' : '' }}> NO</label>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: flex-end; gap: 5px; margin-top: 2px;">
                                    <span style="font-size: 7.5pt; white-space: nowrap;">If YES, please specify ID No:</span>
                                    <input type="text" value="{{ $check->txtarea12 ?? '' }}" style="flex: 1; border: none; border-bottom: 1px solid black; height: 14px; font-size: 8pt; font-weight: bold;">
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table id="pds-table" style="border: 2px solid #000; border-top: 0; width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr>
                        {{-- LEFT COLUMN: REFERENCES & DECLARATION --}}
                        <td style="width: 75%; vertical-align: top; padding: 0; border-right: 1px solid #000;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td colspan="3" class="s-label align-top" style="font-size: 7.5pt; border-bottom: 1px solid #000; padding: 5px;">
                                        <span class="count">41.</span> REFERENCES <span style="font-size: 6.5pt;">(Person not related by consanguinity or affinity to applicant /appointee)</span>
                                    </td>
                                </tr>
                                <tr style="text-align: center; font-weight: bold; font-size: 7pt;">
                                    <td style="width: 40%; border-right: 1px solid #000; border-bottom: 1px solid #000; height: 18px;">NAME</td>
                                    <td style="width: 40%; border-right: 1px solid #000; border-bottom: 1px solid #000;">OFFICE / RESIDENTIAL ADDRESS</td>
                                    <td style="width: 20%; border-bottom: 1px solid #000;">CONTACT NO. AND/OR EMAIL</td>
                                </tr>
                                @php $ref_count = 3; $refs = collect($reference_records)->take($ref_count); @endphp
                                @foreach($refs as $reference)
                                <tr style="font-size: 8pt; height: 22px;">
                                    <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 0 5px;"><b>{{ $reference->reference_name ?? '' }}</b></td>
                                    <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 0 5px;"><b>{{ $reference->reference_address ?? '' }}</b></td>
                                    <td style="border-bottom: 1px solid #000; padding: 0 5px; text-align: center;"><b>{{ $reference->reference_telno ?? '' }}</b></td>
                                </tr>
                                @endforeach
                                @for($i = $refs->count(); $i < $ref_count; $i++)
                                <tr style="height: 22px;">
                                    <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"></td>
                                    <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"></td>
                                    <td style="border-bottom: 1px solid #000;"></td>
                                </tr>
                                @endfor

                                <tr>
                                    <td colspan="3" class="align-top" style="font-size: 7.5pt; text-align: justify; padding: 5px 10px; line-height: 1.2; border-bottom: 1px solid #000;">
                                        <span class="count">42.</span> I declare under oath that I have personally accomplished this Personal Data Sheet which is a true, correct, and complete statement pursuant to the provisions of pertinent laws, rules, and regulations of the Republic of the Philippines. I authorize the agency head/authorized representative to verify/validate the contents stated herein. I agree that any misrepresentation made in this document and its attachments shall cause the filing of administrative/criminal case/s against me.
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" style="padding: 0;">
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="width: 50%; border-right: 1px solid #000; padding: 5px; vertical-align: top;">
                                                    <div class="s-label" style="font-size: 7pt; padding: 2px 5px; margin-bottom: 5px;">
                                                        Government Issued ID <span style="font-size: 6pt;">(i.e.Passport, GSIS, SSS, PRC, Driver's License, etc.)</span><br>
                                                        <b>PLEASE INDICATE ID Number and Date of Issuance</b>
                                                    </div>
                                                    @php $gov = collect($govid_records)->first(); @endphp
                                                    <div style="font-size: 7.5pt; margin-bottom: 3px; display: flex;">
                                                        <span style="width: 120px;">Government Issued ID:</span>
                                                        <span style="flex: 1; border-bottom: 1px solid #000; font-weight: bold; text-align: center;">{{ $gov->govid_name ?? '' }}</span>
                                                    </div>
                                                    <div style="font-size: 7.5pt; margin-bottom: 3px; display: flex;">
                                                        <span style="width: 120px;">ID/License/Passport No.:</span>
                                                        <span style="flex: 1; border-bottom: 1px solid #000; font-weight: bold; text-align: center;">{{ $gov->govid_no ?? '' }}</span>
                                                    </div>
                                                    <div style="font-size: 7.5pt; margin-bottom: 3px; display: flex;">
                                                        <span style="width: 120px;">Date/Place of Issuance:</span>
                                                        <span style="flex: 1; border-bottom: 1px solid #000; font-weight: bold; text-align: center;">{{ $gov->govid_dateplace ?? '' }}</span>
                                                    </div>
                                                </td>
                                                <td style="width: 50%; padding: 0; vertical-align: top;">
                                                    <div style="height: 60px; text-align: center; display: flex; align-items: center; justify-content: center; font-size: 7.5pt; color: #02066F; font-style: italic;">
                                                        (wet signature/e-signature/digital certificate)
                                                    </div>
                                                    <div class="s-label" style="text-align: center; font-size: 7pt; border-top: 1px solid #000; border-bottom: 1px solid #000; height: 15px;">Signature (Sign inside the box)</div>
                                                    <div style="height: 25px; border-bottom: 1px solid #000; font-weight: bold; text-align: center; line-height: 25px; font-size: 8pt;">{{ now()->format('d/m/Y') }}</div>
                                                    <div class="s-label" style="text-align: center; font-size: 7pt; height: 15px;">Date Accomplished</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        {{-- RIGHT COLUMN: PHOTO & THUMBMARK --}}
                        <td style="width: 25%; vertical-align: top; padding: 10px;">
                            <div style="border: 2px solid #000; width: 130px; height: 170px; margin: 0 auto 5px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 5px;">
                                <div style="font-size: 7pt; line-height: 1.1;">
                                    Passport-sized unfiltered digital picture taken within the last 6 months 4.5 cm. X 3.5 cm
                                </div>
                            </div>
                            <div class="s-label" style="text-align: center; font-size: 7pt; border: 1px solid #000; width: 130px; margin: 0 auto 20px;">PHOTO</div>

                            <div style="border: 2px solid #000; width: 130px; height: 100px; margin: 0 auto 5px;"></div>
                            <div class="s-label" style="text-align: center; font-size: 7pt; border: 1px solid #000; width: 130px; margin: 0 auto;">Right Thumbmark</div>
                        </td>
                    </tr>

                    {{-- OATH SECTION --}}
                    <tr>
                        <td colspan="2" style="border-top: 1px solid #000; padding: 10px 20px; text-align: center; font-size: 8pt;">
                            SUBSCRIBED AND SWORN to before me this <span style="display: inline-block; width: 200px; border-bottom: 1px solid #000;"></span>, affiant exhibiting his/her validly issued government ID as indicated above.
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 20px 0;">
                            <div style="width: 300px; margin: 0 auto; border: 2px solid #000;">
                                <div style="height: 60px; display: flex; align-items: center; justify-content: center; font-size: 7.5pt; color: #02066F; font-style: italic; text-align: center; padding: 0 10px;">
                                    (wet signature/e-signature/digital certificate except for notary public)
                                </div>
                                <div class="s-label" style="text-align: center; font-size: 8pt; border-top: 1px solid #000; padding: 2px 0;">Person Administering Oath</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="font-family: 'Arial Narrow', sans-serif; font-size: 9px; text-align: right; padding: 5px 0;">
                <b><i>CS FORM 212 (Revised 2025), Page 4 of 4</i></b>
            </div>
        </form>
    </div>
</div>
