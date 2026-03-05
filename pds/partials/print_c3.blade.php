<div id="C3">
    <div class="pds-preview-inner table-responsive">
        <form action="">
            <table id="pds-table">
                {{-- VI. VOLUNTARY WORK - Always show --}}
                <tbody class="table-body">
                    <tr>
                        <td colspan="12" class="text-white separator"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">
                            VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY
                            ORGANIZATION/S
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="6" rowspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">
                            <span class="count float-left">29.</span> NAME & ADDRESS OF ORGANIZATION<br>(Write in full)
                        </td>
                        <td colspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">
                            INCLUSIVE DATES<br>(dd/mm/yyyy)
                        </td>
                        <td colspan="1" rowspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">
                            NUMBER OF HOURS
                        </td>
                        <td colspan="3" rowspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">
                            POSITION / NATURE OF WORK
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="1" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">
                            From
                        </td>
                        <td colspan="1" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">
                            To
                        </td>
                    </tr>

                    @forelse($voluntary_work as $voluntary)
                        <tr class="text-center">
                            <td colspan="6"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ $voluntary->name_and_address_of_organization ?? 'N/A' }}</span></td>
                            <td colspan="1"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ !empty($voluntary->volunteer_from) ? \Carbon\Carbon::parse($voluntary->volunteer_from)->format('d/m/Y') : 'N/A' }}</span>
                            </td>
                            <td colspan="1"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ !empty($voluntary->volunteer_to) ? \Carbon\Carbon::parse($voluntary->volunteer_to)->format('d/m/Y') : 'N/A' }}</span>
                            </td>
                            <td colspan="1"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ $voluntary->number_of_hours ?? 'N/A' }}</span></td>
                            <td colspan="3"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ $voluntary->nature_of_work ?? 'N/A' }}</span></td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="6" style="text-align: center;"><span
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
                            <td colspan="3" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                        </tr>
                        @for ($i = 0; $i < 7; $i++)
                            <tr class="text-center" style="height: 25px;">
                                <td colspan="6"></td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                                <td colspan="3"></td>
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

                {{-- VII. LEARNING AND DEVELOPMENT - Always show --}}
                <tbody class="table-body">
                    <tr>
                        <td colspan="12" class="text-white separator"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">VII. LEARNING
                            AND DEVELOPMENT (L&D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED</td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="6" rowspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0; width: 40%;">
                            <span class="count float-left">30.</span> TITLE OF LEARNING AND DEVELOPMENT
                            INTERVENTIONS/TRAINING PROGRAMS<br>
                            (Write in full)
                        </td>
                        <td colspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0; width: 15%;">INCLUSIVE DATES
                            OF ATTENDANCE <br>(dd/mm/yyyy)
                        </td>
                        <td colspan="1" rowspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0; width: 8%;">NUMBER OF
                            HOURS
                        </td>
                        <td colspan="1" rowspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0; width: 10%;">Type of LD
                            <br>(Managerial/<br>Supervisory/<br>Technical/etc)</td>
                        <td colspan="2" rowspan="2" class="s-label border-bottom-0"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0; width: 25%;">
                            CONDUCTED/SPONSORED BY<br>(Write in full)</td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="1" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">From</td>
                        <td colspan="1" class="s-label"
                            style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">To</td>
                    </tr>

                    @forelse($training_records as $training)
                        <tr>
                            <td colspan="6" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ $training->title ?? 'N/A' }}</span></td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ !empty($training->training_from) ? \Carbon\Carbon::parse($training->training_from)->format('d/m/Y') : 'N/A' }}</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ !empty($training->training_to) ? \Carbon\Carbon::parse($training->training_to)->format('d/m/Y') : 'N/A' }}</span>
                            </td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ $training->number_of_hours ?? 'N/A' }}</span></td>
                            <td colspan="1" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ $training->training_type ?? 'N/A' }}</span></td>
                            <td colspan="2" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ $training->sponsor ?? 'N/A' }}</span></td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="6" style="text-align: center;"><span
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
                            <td colspan="2" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                        </tr>
                        @for ($i = 0; $i < 21; $i++)
                            <tr style="height: 25px;">
                                <td colspan="6"></td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                                <td colspan="2"></td>
                            </tr>
                        @endfor
                    @endforelse
                </tbody>

                <tbody class="table-body">
                    <tr>
                        <td colspan="12" class="text-danger fw-bold text-center bg-transparent">
                            <i>(Continue on a separate sheet if necessary)</i>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table id="pds-table" style="border-top: 0;">

                {{-- VIII. OTHER INFORMATION - Always show --}}
                <tbody class="table-body">
                    <tr>
                        <td colspan="12" class="text-white separator">VIII. OTHER INFORMATION</td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="3" class="s-label" style="width: 30%;">
                            <span class="count float-left"
                                style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">31.</span>
                            SPECIAL SKILLS and HOBBIES
                        </td>
                        <td colspan="6" class="s-label" style="width: 40%;">
                            <span class="count float-left"
                                style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">32.</span>
                            NON-ACADEMIC DISTINCTIONS / RECOGNITION<br>(Write in full)
                        </td>
                        <td colspan="3" class="s-label" style="width: 30%;">
                            <span class="count float-left"
                                style="font-family: 'Arial Narrow', sans-serif; font-size: 7pt; padding: 0;">33.</span>
                            MEMBERSHIP IN ASSOCIATION/ORGANIZATION<br>(Write in full)
                        </td>
                    </tr>

                    @forelse($other_info as $info)
                        <tr class="text-center">
                            <td colspan="3"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block; ">
                                    {{ $info->skills ?? 'N/A' }}</span></td>
                            <td colspan="6"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block; ">
                                    {{ $info->academic ?? 'N/A' }}</span></td>
                            <td colspan="3"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">
                                    {{ $info->membership ?? 'N/A' }}</span></td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="3" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="6" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                            <td colspan="3" style="text-align: center;"><span
                                    style="font-family: 'Arial Narrow', sans-serif; font-size: 10pt; font-weight: bold; display: block;">N/A</span>
                            </td>
                        </tr>
                        @for ($i = 0; $i < 7; $i++)
                            <tr class="text-center" style="height: 25px;">
                                <td colspan="3"></td>
                                <td colspan="6"></td>
                                <td colspan="3"></td>
                            </tr>
                        @endfor
                    @endforelse
                </tbody>

                <tbody class="table-body">
                    <tr>
                        <td colspan="12" class="text-danger fw-bold text-center bg-transparent" style="padding: 0;">
                            <i>(Continue on separate sheet if necessary)</i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-center" style="padding: 0;"><i><b>SIGNATURE</b></i></td>
                        <td colspan="6"></td>
                        <td colspan="3" class="text-center" style="padding: 0;"><i><b>DATE</b></i></td>
                    </tr>
                </tbody>
            </table>
            <div style="font-family: 'Arial Narrow', sans-serif; font-size: 9px; text-align: right; padding: 5px 0;">
                <b><i>CS FORM 212 (Revised 2025), Page 3 of 4</i></b>
            </div>
        </form>
    </div>
</div>