<div id="C2">
    <div class="pds-preview-inner table-responsive">
        <form action="">
            <table id="pds-table">
                {{-- IV. CIVIL SERVICE ELIGIBILITY - Always show --}}
                <tbody class="table-body">
                    <tr>
                        <td colspan="12" class="text-white separator">IV. CIVIL SERVICE ELIGIBILITY</td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="5" rowspan="2" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; width: 30%;">
                            <span class="count float-left">27.</span>CAREER SERVICE/RA 1080 (BOARD/BAR) UNDER SPECIAL
                            LAWS/CES/CSEE BARANGAY ELIGIBILITY/DRIVER'S LICENSE
                        </td>
                        <td colspan="1" rowspan="2" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">RATING<br>(If Applicable)
                        </td>
                        <td colspan="2" rowspan="2" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">DATE OF EXAMINATION /
                            CONFERMENT</td>
                        <td colspan="2" rowspan="2" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">PLACE OF EXAMINATION /
                            CONFERMENT</td>
                        <td colspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">LICENSE<br>(If Applicable)
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="1" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">NUMBER</td>
                        <td colspan="1" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">Date of Validity</td>
                    </tr>

                    @forelse($csc_eligibility as $eligibility)
                        <tr class="text-center">
                            <td colspan="5" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block; ">{{ $eligibility->license_name ?? 'N/A' }}</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block; ">{{ $eligibility->rating ?? 'N/A' }}</span>
                            </td>
                            <td colspan="2" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block; ">{{ !empty($eligibility->date_of_examination) ? \Carbon\Carbon::parse($eligibility->date_of_examination)->format('d/m/Y') : 'N/A' }}</span>
                            </td>
                            <td colspan="2" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block; ">{{ $eligibility->place_of_examination ?? 'N/A' }}</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block; ">{{ $eligibility->license_no ?? 'N/A' }}</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block; ">{{ !empty($eligibility->date_of_validity) ? \Carbon\Carbon::parse($eligibility->date_of_validity)->format('d/m/Y') : 'N/A' }}</span>
                            </td>
                        </tr>
                    @empty
                        {{-- Empty rows when no data --}}
                        <tr class="text-center">
                            <td colspan="5" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="2" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="2" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                        </tr>
                        @for ($i = 0; $i < 7; $i++)
                            <tr class="text-center" style="height: 25px;">
                                <td colspan="5"></td>
                                <td colspan="1"></td>
                                <td colspan="2"></td>
                                <td colspan="2"></td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                            </tr>
                        @endfor
                    @endforelse
                </tbody>

                <tbody class="table-body">
                    <tr>
                        <td colspan="12" class="text-danger fw-bold text-center bg-transparent">
                            <i>(Continue on separate sheet if necessary)</i>
                        </td>
                    </tr>
                </tbody>

                {{-- V. WORK EXPERIENCE - Always show --}}
                <tbody class="table-body">
                    <tr>
                        <td colspan="12" class="text-white separator">
                            V. WORK EXPERIENCE<br>
                            <small><i>(Include private employment. Start from your recent work) Description of duties
                                    should be indicated in the attached Work Experience sheet.</i></small>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; width: 10%; font-size: 7pt;">
                            <span class="count float-left">28.</span>
                            INCLUSIVE DATES (dd/mm/yyyy)
                        </td>
                        <td colspan="4" rowspan="2" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">
                            POSITION TITLE<br>
                            Write in full/Do not abbreviate
                        </td>
                        <td colspan="2" rowspan="2" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">
                            DEPARTMENT/AGENCY/OFFICE/COMPANY<br>
                            (Write in full/Do not abbreviate)
                        </td>
                        <td colspan="1" rowspan="2" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">MONTHLY<br>SALARY</td>
                        <td colspan="1" rowspan="2" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;"><small>SALARY/ JOB/
                                PAY<br>GRADE (if applicable)& STEP (Format "00-0")/ INCREMENT</small></td>
                        <td colspan="1" rowspan="2" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">STATUS OF<br>APPOINTMENT
                        </td>
                        <td colspan="1" rowspan="2" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">GOV'T SERVICE<br>
                            <small>(Y/ N)</small>
                        </td>
                    </tr>

                    <!-- Inclusive Dates Sub-header -->
                    <tr>
                        <td colspan="1" class="s-label text-center"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">From</td>
                        <td colspan="1" class="s-label text-center"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt;">To</td>
                    </tr>

                    @forelse($work_experience as $entry)
                        <tr>
                            <td colspan="1" style="text-align: center;">
                                <span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ \Carbon\Carbon::parse($entry->employed_from)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td colspan="1" style="text-align: center;">
                                <span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ !empty($entry->employed_to) ? \Carbon\Carbon::parse($entry->employed_to)->format('d/m/Y') : 'Present' }}
                                </span>
                            </td>
                            <td colspan="4" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">{{ $entry->position_title ?? 'N/A' }}</span>
                            </td>
                            <td colspan="2" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">{{ $entry->department ?? 'N/A' }}</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">₱{{ number_format($entry->salary, 2) }}</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">{{ $entry->salary_grade ?? 'N/A' }}</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">{{ $entry->appointment_status ?? 'N/A' }}</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">{{ ($entry->is_government == 'Y') ? 'Y' : 'N' }}</span>
                            </td>
                        </tr>
                    @empty
                        {{-- Empty rows when no data --}}
                        <tr class="text-center">
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="4" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="2" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                        </tr>
                        @for ($i = 0; $i < 28; $i++)
                            <tr style="height: 25px;">
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                                <td colspan="4"></td>
                                <td colspan="2"></td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                            </tr>
                        @endfor
                    @endforelse
                </tbody>

                <tbody class="table-body">
                    <tr>
                        <td colspan="12" class="text-danger fw-bold text-center bg-transparent">
                            <i>(Continue on separate sheet if necessary)</i>
                        </td>
                    </tr>
                    <tr style="height: 25px;">
                        <td colspan="1" class="text-center"><i><b>SIGNATURE</b></i></td>
                        <td colspan="6"></td>
                        <td colspan="2" class="text-center"><i><b>DATE</b></i></td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>
            <div style="font-family: 'Arial Narrow', sans-serif; font-size: 9px; text-align: right; padding: 5px 0;">
                <b><i>CS FORM 212 (Revised 2025), Page 2 of 4</i></b>
            </div>
        </form>
    </div>
</div>