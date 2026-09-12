/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Universal Odisha Location Cascading & Auto-population Engine
 * Dynamic AJAX loading for 51,804 Odisha Districts, Blocks, Gram Panchayats, Villages & Pincodes
 */

(function () {
    'use strict';

    // Helper to get base API URL
    function getApiBase() {
        if (typeof window.SVPL_BASE_URL !== 'undefined') {
            return window.SVPL_BASE_URL.replace(/\/+$/, '');
        }
        const path = window.location.pathname;
        const match = path.match(/^(\/svpl[-_]?web)/i);
        return match ? match[1] : '';
    }

    const apiBase = getApiBase();

    class LocationCascader {
        constructor(container) {
            this.container = container;
            this.districtSelect = container.querySelector('select[name="district"], #selectDistrict, .select-district');
            this.blockSelect = container.querySelector('select[name="block"], #selectBlock, .select-block');
            this.gpInput = container.querySelector('select[name="gram_panchayat"], input[name="gram_panchayat"], #selectGp, #inputGp, .select-gp');
            this.villageInput = container.querySelector('select[name="village"], input[name="village"], #selectVillage, #inputVillage, .select-village');
            this.pincodeInput = container.querySelector('input[name="pincode"], #inputPincode, .input-pincode');

            if (!this.districtSelect) return;

            this.initialDistrict = this.districtSelect.getAttribute('data-initial') || this.districtSelect.value || '';
            this.initialBlock = this.blockSelect ? (this.blockSelect.getAttribute('data-initial') || this.blockSelect.value || '') : '';
            this.initialGp = this.gpInput ? (this.gpInput.getAttribute('data-initial') || this.gpInput.value || '') : '';
            this.initialVillage = this.villageInput ? (this.villageInput.getAttribute('data-initial') || this.villageInput.value || '') : '';
            this.initialPincode = this.pincodeInput ? (this.pincodeInput.getAttribute('data-initial') || this.pincodeInput.value || '') : '';

            this.villagesMap = [];

            this.setupDatalists();
            this.bindEvents();
            this.loadDistricts();
        }

        setupDatalists() {
            // Setup datalists for GP and Village if they are inputs
            if (this.gpInput && this.gpInput.tagName === 'INPUT') {
                let gpListId = this.gpInput.getAttribute('list');
                if (!gpListId) {
                    gpListId = 'gpList_' + Math.random().toString(36).substring(2, 9);
                    this.gpInput.setAttribute('list', gpListId);
                }
                let gpList = document.getElementById(gpListId);
                if (!gpList) {
                    gpList = document.createElement('datalist');
                    gpList.id = gpListId;
                    this.gpInput.parentNode.appendChild(gpList);
                }
                this.gpDatalist = gpList;
            }

            if (this.villageInput && this.villageInput.tagName === 'INPUT') {
                let vListId = this.villageInput.getAttribute('list');
                if (!vListId) {
                    vListId = 'villageList_' + Math.random().toString(36).substring(2, 9);
                    this.villageInput.setAttribute('list', vListId);
                }
                let vList = document.getElementById(vListId);
                if (!vList) {
                    vList = document.createElement('datalist');
                    vList.id = vListId;
                    this.villageInput.parentNode.appendChild(vList);
                }
                this.villageDatalist = vList;
            }
        }

        bindEvents() {
            if (this.districtSelect) {
                this.districtSelect.addEventListener('change', () => {
                    const district = this.districtSelect.value;
                    if (district) {
                        this.loadBlocks(district);
                    } else {
                        this.resetBlocks();
                        this.resetGps();
                        this.resetVillages();
                    }
                });
            }

            if (this.blockSelect) {
                this.blockSelect.addEventListener('change', () => {
                    const block = this.blockSelect.value;
                    if (block) {
                        this.loadGps(block);
                    } else {
                        this.resetGps();
                        this.resetVillages();
                    }
                });
            }

            if (this.gpInput) {
                const onGpChange = () => {
                    const gp = this.gpInput.value.trim();
                    const block = this.blockSelect ? this.blockSelect.value : '';
                    if (gp) {
                        this.loadVillages(gp, block);
                    } else {
                        this.resetVillages();
                    }
                };
                this.gpInput.addEventListener('change', onGpChange);
                this.gpInput.addEventListener('input', () => {
                    if (this.gpDatalist) {
                        // Check if typed value matches a datalist option
                        const opts = Array.from(this.gpDatalist.options).map(o => o.value);
                        if (opts.includes(this.gpInput.value.trim())) {
                            onGpChange();
                        }
                    }
                });
            }

            if (this.villageInput) {
                const onVillageChange = () => {
                    const vName = this.villageInput.value.trim().toLowerCase();
                    const match = this.villagesMap.find(v => (v.village || '').toLowerCase() === vName);
                    if (match && match.pincode && this.pincodeInput && !this.pincodeInput.value) {
                        this.pincodeInput.value = match.pincode;
                    }
                };
                this.villageInput.addEventListener('change', onVillageChange);
                this.villageInput.addEventListener('input', () => {
                    if (this.villageDatalist) {
                        const opts = Array.from(this.villageDatalist.options).map(o => o.value);
                        if (opts.includes(this.villageInput.value.trim())) {
                            onVillageChange();
                        }
                    }
                });
            }

            if (this.pincodeInput) {
                this.pincodeInput.addEventListener('blur', () => {
                    const pin = this.pincodeInput.value.trim();
                    if (/^\d{6}$/.test(pin) && (!this.districtSelect.value || !this.blockSelect.value)) {
                        this.lookupPincode(pin);
                    }
                });
            }
        }

        async loadDistricts() {
            try {
                const res = await fetch(`${apiBase}/api/locations/districts`);
                const data = await res.json();
                if (data.success && Array.isArray(data.districts)) {
                    const currentVal = this.initialDistrict || this.districtSelect.value;
                    this.districtSelect.innerHTML = '<option value="">Select District</option>';
                    data.districts.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d;
                        opt.textContent = d;
                        if (d.toLowerCase() === currentVal.toLowerCase()) {
                            opt.selected = true;
                        }
                        this.districtSelect.appendChild(opt);
                    });

                    if (this.districtSelect.value) {
                        this.loadBlocks(this.districtSelect.value, this.initialBlock);
                    }
                }
            } catch (err) {
                console.warn('[LocationCascader] Failed to load districts:', err);
            }
        }

        async loadBlocks(district, preselectBlock = '') {
            if (!this.blockSelect) return;
            this.blockSelect.disabled = true;
            this.blockSelect.innerHTML = '<option value="">Loading blocks...</option>';

            try {
                const res = await fetch(`${apiBase}/api/locations/blocks?district=${encodeURIComponent(district)}`);
                const data = await res.json();
                this.blockSelect.disabled = false;

                if (data.success && Array.isArray(data.blocks)) {
                    this.blockSelect.innerHTML = '<option value="">Select Block / Municipality</option>';
                    const targetBlock = preselectBlock || this.initialBlock || '';
                    data.blocks.forEach(b => {
                        const opt = document.createElement('option');
                        opt.value = b;
                        opt.textContent = b;
                        if (targetBlock && b.toLowerCase() === targetBlock.toLowerCase()) {
                            opt.selected = true;
                        }
                        this.blockSelect.appendChild(opt);
                    });

                    if (this.blockSelect.value) {
                        this.loadGps(this.blockSelect.value, this.initialGp);
                    }
                } else {
                    this.blockSelect.innerHTML = '<option value="">No blocks found</option>';
                }
            } catch (err) {
                this.blockSelect.disabled = false;
                this.blockSelect.innerHTML = '<option value="">Failed to load blocks</option>';
            }
        }

        async loadGps(block, preselectGp = '') {
            if (!this.gpInput) return;

            try {
                const res = await fetch(`${apiBase}/api/locations/gps?block=${encodeURIComponent(block)}`);
                const data = await res.json();

                if (data.success && Array.isArray(data.gps)) {
                    if (this.gpInput.tagName === 'SELECT') {
                        this.gpInput.innerHTML = '<option value="">Select Gram Panchayat / Ward</option>';
                        const targetGp = preselectGp || this.initialGp || '';
                        data.gps.forEach(gp => {
                            const opt = document.createElement('option');
                            opt.value = gp;
                            opt.textContent = gp;
                            if (targetGp && gp.toLowerCase() === targetGp.toLowerCase()) {
                                opt.selected = true;
                            }
                            this.gpInput.appendChild(opt);
                        });
                        if (this.gpInput.value) {
                            this.loadVillages(this.gpInput.value, block, this.initialVillage);
                        }
                    } else if (this.gpDatalist) {
                        this.gpDatalist.innerHTML = '';
                        data.gps.forEach(gp => {
                            const opt = document.createElement('option');
                            opt.value = gp;
                            this.gpDatalist.appendChild(opt);
                        });
                        if (preselectGp) {
                            this.gpInput.value = preselectGp;
                            this.loadVillages(preselectGp, block, this.initialVillage);
                        }
                    }
                }
            } catch (err) {
                console.warn('[LocationCascader] Failed to load GPs:', err);
            }
        }

        async loadVillages(gp, block = '', preselectVillage = '') {
            if (!this.villageInput) return;

            try {
                let url = `${apiBase}/api/locations/villages?gp=${encodeURIComponent(gp)}`;
                if (block) {
                    url += `&block=${encodeURIComponent(block)}`;
                }
                const res = await fetch(url);
                const data = await res.json();

                if (data.success && Array.isArray(data.villages)) {
                    this.villagesMap = data.villages;

                    if (this.villageInput.tagName === 'SELECT') {
                        this.villageInput.innerHTML = '<option value="">Select Village / Locality</option>';
                        const targetV = preselectVillage || this.initialVillage || '';
                        data.villages.forEach(v => {
                            const opt = document.createElement('option');
                            opt.value = v.village;
                            opt.textContent = v.village + (v.pincode ? ` (${v.pincode})` : '');
                            if (targetV && v.village.toLowerCase() === targetV.toLowerCase()) {
                                opt.selected = true;
                            }
                            this.villageInput.appendChild(opt);
                        });
                    } else if (this.villageDatalist) {
                        this.villageDatalist.innerHTML = '';
                        data.villages.forEach(v => {
                            const opt = document.createElement('option');
                            opt.value = v.village;
                            if (v.pincode) {
                                opt.label = `PIN: ${v.pincode}`;
                            }
                            this.villageDatalist.appendChild(opt);
                        });
                        if (preselectVillage) {
                            this.villageInput.value = preselectVillage;
                        }
                    }

                    // Auto-suggest pincode if only 1 village or all have same pincode
                    const firstPin = data.villages.find(v => v.pincode)?.pincode;
                    if (firstPin && this.pincodeInput && !this.pincodeInput.value) {
                        this.pincodeInput.value = firstPin;
                    }
                }
            } catch (err) {
                console.warn('[LocationCascader] Failed to load villages:', err);
            }
        }

        async lookupPincode(pincode) {
            try {
                const res = await fetch(`${apiBase}/api/locations/pincode?pincode=${encodeURIComponent(pincode)}`);
                const data = await res.json();
                if (data.success && Array.isArray(data.records) && data.records.length > 0) {
                    const first = data.records[0];
                    if (first.district && this.districtSelect) {
                        this.districtSelect.value = first.district;
                        this.loadBlocks(first.district, first.block);
                        if (first.gram_panchayat && this.gpInput) {
                            this.gpInput.value = first.gram_panchayat;
                        }
                        if (first.village && this.villageInput) {
                            this.villageInput.value = first.village;
                        }
                    }
                }
            } catch (err) {
                console.warn('[LocationCascader] Pincode lookup error:', err);
            }
        }

        resetBlocks() {
            if (this.blockSelect) {
                this.blockSelect.innerHTML = '<option value="">Select District first</option>';
            }
        }

        resetGps() {
            if (this.gpInput) {
                if (this.gpInput.tagName === 'SELECT') {
                    this.gpInput.innerHTML = '<option value="">Select Block first</option>';
                } else {
                    if (this.gpDatalist) this.gpDatalist.innerHTML = '';
                    this.gpInput.value = '';
                }
            }
        }

        resetVillages() {
            if (this.villageInput) {
                if (this.villageInput.tagName === 'SELECT') {
                    this.villageInput.innerHTML = '<option value="">Select GP first</option>';
                } else {
                    if (this.villageDatalist) this.villageDatalist.innerHTML = '';
                    this.villageInput.value = '';
                }
            }
            this.villagesMap = [];
        }
    }

    // Auto initialize on DOM ready
    function initCascaders() {
        const forms = document.querySelectorAll('form, .location-cascade-group');
        forms.forEach(f => {
            const districtEl = f.querySelector('select[name="district"], #selectDistrict, .select-district');
            if (districtEl && !f._locationCascaderInitialized) {
                f._locationCascaderInitialized = true;
                new LocationCascader(f);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCascaders);
    } else {
        initCascaders();
    }

    window.initLocationCascaders = initCascaders;
})();
