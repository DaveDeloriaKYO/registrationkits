# Existing Personnel + Registration Kit Logic

## Personnel workflow
1. **Add Personnel** opens a master/quarter modal.
2. The **Existing Personnel / Quarterly Renewal** dropdown is populated from `personnel_info`.
3. Selecting an existing person keeps the same `personnel_info.id` and creates a new row in `personnel_quarterly_history` for the selected Year + Quarter.
4. A duplicate Personnel + Year + Quarter is rejected by both application validation and the database unique key.
5. Choosing **New Personnel** creates a new master record and its first quarterly history row.
6. Existing history is never overwritten during quarterly renewal.

## NID / Registration Kit matching
The personnel `regno` is matched to `registrationdetails.regno`.
- If a matching registration kit exists, the Add/Edit/Renew modal shows the kit's Reg. Code, Computer, Machine ID, Kit Serial, Serial Number, Property Number, RCS Username, and Registration Center.
- The kit password is intentionally not returned to the personnel page.
- If no kit record exists, personnel entry is still allowed; the UI clearly indicates that no kit record was found.

## Why this design
`personnel_info` remains the master identity record, while `personnel_quarterly_history` is the assignment/contract history. `registrationdetails` remains the source of truth for registration kit information. This avoids duplicating kit data into personnel history and allows kit details to change without rewriting historical personnel records.
