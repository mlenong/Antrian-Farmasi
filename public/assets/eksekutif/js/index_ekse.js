const socket = io('http://172.16.15.25:3000');

    async function fetchData(url) {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        } catch (error) {
            showMessage('Failed to fetch data', 'error');
            console.error('Fetch error:', error);
            return [];
        }
    }

    async function callAntrianEkse(id) {
        try {
            const response = await fetch(`antrian/call-ekse/${id}`, { method: 'POST' });
            if (!response.ok) throw new Error('Gagal memanggil antrian');
            const data = await response.json();
            if (data.antrian_aktif === 'Y') {
                showMessage('Antrian sudah dipanggil', 'error');
                return;
            }
            socket.emit('call', data);

            // Cek apakah antrian ada sebelum diproses
            // if (data.antrian) {
            //     playAudioSequence(data.antrian); // Panggil playAudioSequence hanya jika antrian ada
            // } else {
            //     showMessage('Antrian tidak ditemukan', 'error');
            // }

            showMessage('Antrian berhasil dipanggil', 'success');
            toggleButtons('recall'); // Menampilkan tombol Recall setelah Call
        } catch (error) {
            showMessage('Gagal memanggil antrian', 'error');
        }
    }


    async function updateProsesEkse(id) {
        try {
            const response = await fetch(`antrian/proses-ekse/${id}`, { method: 'POST' });
            if (!response.ok) throw new Error('Failed to update process');
            socket.emit('process', { id });
            showMessage('Proses berhasil diperbarui', 'success');
        } catch (error) {
            showMessage('Gagal memperbarui proses', 'error');
        }
    }

    async function nextAntrianEkse() {
        try {
            const response = await fetch('antrian/nextAntrian-ekse', { method: 'POST' });
            if (!response.ok) throw new Error('Failed to get next queue');
            const data = await response.json();
            if (data.antrian) {
                currentAntrian = data.antrian;
                socket.emit('call', data.antrian);
                // playAudioSequence(data.antrian.antrian);
                showMessage(`Antrian ${data.antrian.antrian} dipanggil`, 'success');
                toggleButtons('recall'); // Menampilkan tombol Recall setelah Next
            } else {
                showMessage(data.error, 'error');
            }
        } catch (error) {
            showMessage('Gagal memproses next antrian', 'error');
        }
    }

    async function recallAntrianEkse() {
        try {
            const response = await fetch('antrian/recall-ekse', { method: 'POST' });
            if (!response.ok) throw new Error('Failed to recall queue');
            const data = await response.json();
            if (data.antrian) {
                socket.emit('call', data.antrian);
                // playAudioSequence(data.antrian.antrian);
                showMessage(`Antrian ${data.antrian.antrian} dipanggil kembali`, 'success');
                toggleButtons('done'); // Menampilkan tombol Done setelah Recall
            } else {
                showMessage(data.error, 'error');
            }
        } catch (error) {
            showMessage('Gagal memproses recall antrian', 'error');
        }
    }

    async function doneAntrianEkse(id) {
        try {
            const response = await fetch(`antrian/done-ekse/${id}`, { method: 'POST' });
            if (!response.ok) throw new Error('Failed to mark queue as done');

            socket.emit('done', { id });

            showMessage(`Antrian selesai`, 'success');

            toggleButtons('next'); // Menampilkan tombol Next setelah Done

        } catch (error) {
            showMessage('Gagal menyelesaikan antrian', 'error');
        }
    }

    async function doneListEkse(id) {
        try {
            const response = await fetch(`antrian/doneList-ekse/${id}`, { method: 'POST' });
            if (!response.ok) throw new Error('Failed to mark queue as done');

            const data = await response.json();

            if (data.message) {
                socket.emit('done', { id });
                showMessage(`Antrian ${id} selesai`, 'success');
                loadData(); // Refresh data setelah selesai
            } else {
                showMessage(data.error, 'error');
            }
        } catch (error) {
            showMessage('Gagal menyelesaikan antrian', 'error');
        }
    }

    function toggleButtons(action) {
        const callBtn = document.getElementById('nextAntrianBtn');
        const recallBtn = document.getElementById('recallAntrianBtn');
        const doneBtn = document.getElementById('doneAntrianBtn');

        if (action === 'call') {
            callBtn.style.display = 'inline-block'; // Tombol Call muncul
            recallBtn.style.display = 'none'; // Tombol Recall sembunyi
            doneBtn.style.display = 'none'; // Tombol Done sembunyi
        } else if (action === 'recall') {
            callBtn.style.display = 'none'; // Tombol Call sembunyi
            recallBtn.style.display = 'inline-block'; // Tombol Recall muncul
            doneBtn.style.display = 'inline-block'; // Tombol Done muncul
        } else if (action === 'done') {
            callBtn.style.display = 'none'; // Tombol Call sembunyi
            recallBtn.style.display = 'inline-block'; // Tombol Recall tetap muncul
            doneBtn.style.display = 'inline-block'; // Tombol Done sembunyi
        }
    }

    function showMessage(message, type) {
        const messageBox = document.getElementById('message-box');
        messageBox.textContent = message;
        messageBox.className = type;
        messageBox.style.display = 'block'; // Show the message box
        setTimeout(() => {
            messageBox.style.display = 'none'; // Hide the message box after 3 seconds
        }, 3000);
    }

    function showLoading(isLoading) {
        const loadingElement = document.getElementById('loading');
        loadingElement.style.display = isLoading ? 'block' : 'none';
    }

    function playAudioSequence(queueNumber) {
        console.log("Play antrian:", queueNumber);
        const audioFiles = [
            'audio/bell.mp3',
            'audio/antrian.mp3'
        ];

        const digits = queueNumber.toString().split('');
        digits.forEach(digit => {
            audioFiles.push(`audio/${digit}.mp3`);
        });
        audioFiles.push('audio/loket_obat.mp3');

        let index = 0;
        const playNext = () => {
            if (index < audioFiles.length) {
                // console.log("Memutar:", audioFiles[index]);
                const audio = new Audio(audioFiles[index]);
                audio.play();
                audio.onended = playNext;
                index++;
            }
        };
        playNext();
    }

    async function loadData() {
        showLoading(true);
        try {
            const list1 = await fetchData('antrian/hari-ini-ekse');
            const list2 = await fetchData('antrian/racikan-ekse');
            const list3 = await fetchData('antrian/prosesRacikan-ekse'); // Tambahkan bagian ini
            const antrianSekarang = await fetchData('antrian/antrianSekarang-ekse');

            // Update tabel list3
            const list3Container = document.getElementById('list3');
            list3Container.innerHTML = list3.map(item => `
            <tr class="${item.sts_racikan === 'Y' && item.process_at === null ? 'alert-racikan' : ''}">
                <td>${item.antrian}</td>
                <td>${item.no_reg}</td>
                <td>-</td>
                <td><button class="btn btn-outline-danger" onclick="updateProsesEkse('${item.id}')">Proses</button></td>
                <td><button class="btn btn-outline-success" onclick="doneListEkse('${item.id}')">Done</button></td>
            </tr>
        `).join('');

            const list1Container = document.getElementById('list1');
            list1Container.innerHTML = list1.map(item => `
            <tr class="${item.sts_racikan === 'Y' && item.process_at === null ? 'alert-racikan' : ''}">
                <td>${item.antrian}</td>
                <td>${item.no_reg}</td>
                <td><button class="btn btn-outline-primary" onclick="callAntrianEkse('${item.id}')" ${item.antrian_aktif === 'Y' ? 'disabled' : ''}>Call</button></td>
                <td><button class="btn btn-outline-danger" onclick="updateProsesEkse('${item.id}')">Proses</button></td>
                <td><button class="btn btn-outline-success" onclick="doneListEkse('${item.id}')">Done</button></td>
            </tr>
        `).join('');

            const list2Container = document.getElementById('list2');
            list2Container.innerHTML = list2.map(item => `
            <tr class="${item.sts_racikan === 'Y' && item.process_at === null ? 'alert-racikan' : ''}">
                <td>${item.antrian}</td>
                <td>${item.no_reg}</td>
                <td><button class="btn btn-outline-primary" onclick="callAntrianEkse('${item.id}')" ${item.antrian_aktif === 'Y' ? 'disabled' : ''}>Call</button></td>
                <td><button class="btn btn-outline-success" onclick="doneListEkse('${item.id}')">Done</button></td>
                <td>-</td>
            </tr>
        `).join('');

            // Menampilkan antrian aktif
            const antrianSekarangElem = document.getElementById('antrianSekarang');
            const antrianAktif = [...list1, ...list2].find(item => item.antrian_aktif === 'Y');
            if (antrianAktif) {
                antrianSekarangElem.textContent = `${antrianAktif.antrian}`;
            } else if (antrianSekarang && antrianSekarang.antrian) {
                antrianSekarangElem.textContent = `${antrianSekarang.antrian}`;
            } else {
                antrianSekarangElem.textContent = 'END';
            }

            $('#list1').DataTable().clear().destroy();
            $('#list2').DataTable().clear().destroy();
            $('#list3').DataTable().clear().destroy(); 

            $('#list1').DataTable({
                paging: true,
                searching: true,
                info: false,
                dom: 'frtip',
                pageLength: 10,
                language: {
                    search: "",
                    paginate: { previous: "", next: "" }
                },
                columnDefs: [{ orderable: false, targets: [1, 2, 3, 4] }]
            });

            $('#list2').DataTable({
                paging: true,
                searching: true,
                info: false,
                dom: 'frtip',
                pageLength: 10,
                language: {
                    search: "",
                    paginate: { previous: "", next: "" }
                },
                columnDefs: [{ orderable: false, targets: [1, 2, 3, 4] }]
            });

            $('#list3').DataTable({
                paging: true,
                searching: true,
                info: false,
                dom: 'frtip',
                pageLength: 10,
                language: {
                    search: "",
                    paginate: { previous: "", next: "" }
                },
                columnDefs: [{ orderable: false, targets: [1, 2, 3, 4] }]
            });

            if (antrianAktif) {
                toggleButtons('recall');
            } else {
                toggleButtons('call');
            }
        } finally {
            showLoading(false);
        }
    }
    // document.addEventListener('DOMContentLoaded', loadData);
    document.addEventListener('DOMContentLoaded', () => {
        loadData(); // Memuat data saat pertama kali halaman dimuat
        setInterval(loadData, 30000); // reload sendiri untuk memperbarui data
    });

    socket.on('call-ekse', (data) => {
        loadData();
        // playAudioSequence(data.antrian); // Memainkan suara berdasarkan nomor antrian
    });

    socket.on('process-ekse', loadData);
    socket.on('done-ekse', loadData);