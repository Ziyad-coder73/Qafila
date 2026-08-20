@extends('partner.layout')

@section('title', 'Verify Member — Qafila Partner Portal')

@section('content')
    <h1 class="text-2xl font-bold text-slate-900">Verify a Member</h1>
    <p class="mt-1 text-sm text-slate-600">Scan the customer's loyalty QR code or enter their membership number manually.</p>

    <div class="mt-6 bg-white border border-slate-200 rounded-xl p-6">
        <button type="button" id="scan-btn" class="w-full bg-slate-800 text-white font-semibold py-2.5 rounded-lg hover:bg-slate-900 transition">
            Scan QR Code
        </button>
        <p id="scan-unsupported" class="hidden mt-2 text-xs text-amber-600 text-center">
            QR scanning isn't supported in this browser. Please enter the membership number manually below.
        </p>

        <div id="scan-video-wrap" class="hidden mt-4">
            <video id="scan-video" class="w-full rounded-lg bg-black" muted playsinline></video>
            <button type="button" id="scan-cancel" class="mt-2 w-full text-sm text-slate-500 hover:text-slate-700">Cancel scan</button>
        </div>

        <div class="mt-6 flex items-center gap-3 text-xs text-slate-400">
            <div class="flex-1 border-t border-slate-200"></div>
            OR ENTER MANUALLY
            <div class="flex-1 border-t border-slate-200"></div>
        </div>

        <form method="POST" action="{{ route('partner.verify') }}" class="mt-4 flex gap-3">
            @csrf
            <input type="text" name="membership_number" id="membership_number" required autofocus
                placeholder="e.g. QAF-000123"
                class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            <button type="submit" class="bg-blue-700 text-white text-sm font-semibold px-5 rounded-lg hover:bg-blue-800 transition">
                Verify
            </button>
        </form>
    </div>

    <script>
        const scanBtn = document.getElementById('scan-btn');
        const cancelBtn = document.getElementById('scan-cancel');
        const videoWrap = document.getElementById('scan-video-wrap');
        const video = document.getElementById('scan-video');
        const unsupported = document.getElementById('scan-unsupported');
        let stream = null;
        let scanning = false;

        function stopScan() {
            scanning = false;
            videoWrap.classList.add('hidden');
            if (stream) {
                stream.getTracks().forEach((t) => t.stop());
                stream = null;
            }
        }

        async function startScan() {
            if (!('BarcodeDetector' in window)) {
                unsupported.classList.remove('hidden');
                return;
            }

            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            } catch (e) {
                unsupported.textContent = 'Camera access was denied. Please enter the membership number manually.';
                unsupported.classList.remove('hidden');
                return;
            }

            video.srcObject = stream;
            await video.play();
            videoWrap.classList.remove('hidden');
            scanning = true;

            const detector = new BarcodeDetector({ formats: ['qr_code'] });

            const tick = async () => {
                if (!scanning) return;
                try {
                    const codes = await detector.detect(video);
                    if (codes.length > 0) {
                        document.getElementById('membership_number').value = codes[0].rawValue;
                        stopScan();
                        document.querySelector('form[action="{{ route('partner.verify') }}"]').submit();
                        return;
                    }
                } catch (e) {
                    // keep trying
                }
                requestAnimationFrame(tick);
            };

            requestAnimationFrame(tick);
        }

        scanBtn.addEventListener('click', startScan);
        cancelBtn.addEventListener('click', stopScan);
    </script>
@endsection
