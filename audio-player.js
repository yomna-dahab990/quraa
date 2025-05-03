document.addEventListener('DOMContentLoaded', function() {
    // بيانات القراء
    const reciters = [
        {
            id: 'maher_almuaiqly',
            name: 'ماهر المعيقلي',
            image: 'https://i1.sndcdn.com/artworks-bmNkwyPVNMzzPmmv-2OrBfg-t500x500.jpg',
            baseUrl: 'https://download.quranicaudio.com/quran/maher_almuaiqly/'
        },
        {
            id: 'mishari_al_afasy',
            name: 'مشاري العفاسي',
            image: 'https://i1.sndcdn.com/artworks-000235845309-rs851n-t500x500.jpg',
            baseUrl: 'https://download.quranicaudio.com/quran/mishaari_raashid_al_3afaasee/'
        },
        {
            id: 'abdul_baset_abdulsamad',
            name: 'عبد الباسط عبد الصمد',
            image: 'https://www.shorouknews.com/uploadedimages/Other/original/gdxzdfhgdxzfg.jpg',
            baseUrl: 'https://download.quranicaudio.com/quran/abdul_basit_murattal/'
        },
        {
            id: 'ahmad_al_ajmy',
            name: 'أحمد العجمي',
            image: 'https://i.servimg.com/u/f39/18/05/26/73/ahmed-10.png',
            baseUrl: 'https://download.quranicaudio.com/quran/ahmed_ibn_3ali_al-3ajamy/'
        },
        {
            id: 'khalil_al_husary',
            name: 'خليل الحصري',
            image: 'https://i1.sndcdn.com/artworks-000053962227-s5sb1g-t240x240.jpg',
            baseUrl: 'https://download.quranicaudio.com/quran/khalil_al-husary/'
        }
    ];

    // العناصر الرئيسية
    const recitersGrid = document.getElementById('recitersGrid');
    const surahsGrid = document.getElementById('surahsGrid');
    const audioPlayer = document.getElementById('audioPlayer');
    const audioElement = document.getElementById('audioElement');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const currentSurahName = document.getElementById('currentSurahName');
    const currentReciter = document.getElementById('currentReciter');
    const loading = document.getElementById('loading');

    let currentSurah = null;
    let selectedReciter = null;
    let surahs = [];
    let audioCache = new Map(); // كاش لتخزين روابط الصوت

    // عرض رسالة خطأ
    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ff5252;
            color: white;
            padding: 15px 20px;
            border-radius: 4px;
            z-index: 1000;
        `;
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);
        setTimeout(() => errorDiv.remove(), 3000);
    }

    // عرض القراء
    function displayReciters() {
        recitersGrid.innerHTML = '';
        reciters.forEach(reciter => {
            const reciterCard = document.createElement('div');
            reciterCard.className = 'reciter-card';
            reciterCard.innerHTML = `
                <img src="${reciter.image}" alt="${reciter.name}" loading="lazy"
                     onerror="this.src='https://via.placeholder.com/200x200?text=صورة+غير+متوفرة'">
                <h3>${reciter.name}</h3>
            `;
            reciterCard.addEventListener('click', () => selectReciter(reciter));
            recitersGrid.appendChild(reciterCard);
        });
    }

    // اختيار القارئ
    function selectReciter(reciter) {
        selectedReciter = reciter;
        document.querySelectorAll('.reciter-card').forEach(card => card.classList.remove('active'));
        event.currentTarget.classList.add('active');
        loading.style.display = 'block';
        loadSurahs();
    }

    // تحميل السور
    async function loadSurahs() {
        try {
            if (surahs.length === 0) { // تحميل السور مرة واحدة فقط
                const response = await fetch('get_surahs.php');
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                if (!Array.isArray(data)) throw new Error('Invalid data format received');
                surahs = data;
            }
            displaySurahs(surahs);
            surahsGrid.style.display = 'grid';
        } catch (error) {
            console.error('Error loading surahs:', error);
            showError('حدث خطأ في تحميل السور');
        } finally {
            loading.style.display = 'none';
        }
    }

    // عرض السور
    function displaySurahs(surahs) {
        surahsGrid.innerHTML = '';
        const fragment = document.createDocumentFragment();
        
        surahs.forEach(surah => {
            const surahCard = document.createElement('div');
            surahCard.className = 'surah-card';
            surahCard.innerHTML = `
                <h3>${surah.name}</h3>
                <div class="surah-info">
                    <span>سورة ${surah.number}</span>
                    <br>
                    <small>${surah.ayahs} آيات</small>
                </div>
            `;
            surahCard.addEventListener('click', () => playSurah(surah));
            fragment.appendChild(surahCard);
        });
        
        surahsGrid.appendChild(fragment);
    }

    // تشغيل السورة
    async function playSurah(surah) {
        if (!selectedReciter) {
            showError('الرجاء اختيار القارئ أولاً');
            return;
        }

        try {
            currentSurah = surah;
            loading.style.display = 'block';

            // التحقق من الكاش أولاً
            const cacheKey = `${selectedReciter.id}_${surah.number}`;
            let audioUrl;

            if (audioCache.has(cacheKey)) {
                audioUrl = audioCache.get(cacheKey);
            } else {
                const response = await fetch(`get_audio.php?surah=${surah.number}&reciter=${selectedReciter.id}`);
                const data = await response.json();
                if (data.error) throw new Error(data.message || 'حدث خطأ في تحميل الصوت');
                audioUrl = data.audio_url;
                audioCache.set(cacheKey, audioUrl); // تخزين في الكاش
            }

            if (!audioElement.paused) {
                audioElement.pause();
            }

            // تحديث واجهة المشغل
            currentSurahName.textContent = `سورة ${surah.name}`;
            currentReciter.textContent = selectedReciter.name;
            audioPlayer.classList.add('active');

            // تحميل وتشغيل الصوت
            audioElement.src = audioUrl;
            audioElement.preload = 'auto'; // تحميل مسبق للصوت

            const playPromise = audioElement.play();
            if (playPromise !== undefined) {
                playPromise
                    .then(() => {
                        loading.style.display = 'none';
                        updatePlayerUI();
                    })
                    .catch(error => {
                        console.error('Play error:', error);
                        showError('حدث خطأ في تشغيل الصوت');
                        loading.style.display = 'none';
                    });
            }

        } catch (error) {
            console.error('Error details:', error);
            showError(error.message || 'حدث خطأ في تحميل السورة');
            loading.style.display = 'none';
        }
    }

    // تحديث واجهة المشغل
    function updatePlayerUI() {
        if (currentSurah && selectedReciter) {
            playPauseBtn.innerHTML = audioElement.paused ? 
                '<i class="fas fa-play"></i>' : 
                '<i class="fas fa-pause"></i>';
        }
    }

    // التحكم في التشغيل
    playPauseBtn.addEventListener('click', () => {
        if (audioElement.paused) {
            const playPromise = audioElement.play();
            if (playPromise !== undefined) {
                playPromise
                    .then(() => updatePlayerUI())
                    .catch(error => {
                        console.error('Play error:', error);
                        showError('حدث خطأ في تشغيل الصوت');
                    });
            }
        } else {
            audioElement.pause();
            updatePlayerUI();
        }
    });

    // الانتقال للسورة التالية
    nextBtn.addEventListener('click', () => {
        if (currentSurah && currentSurah.number < 114) {
            const nextSurah = surahs.find(s => s.number === currentSurah.number + 1);
            if (nextSurah) playSurah(nextSurah);
        }
    });

    // الانتقال للسورة السابقة
    prevBtn.addEventListener('click', () => {
        if (currentSurah && currentSurah.number > 1) {
            const prevSurah = surahs.find(s => s.number === currentSurah.number - 1);
            if (prevSurah) playSurah(prevSurah);
        }
    });

    // تحديث حالة التشغيل عند انتهاء السورة
    audioElement.addEventListener('ended', () => {
        updatePlayerUI();
    });

    // عرض القراء عند تحميل الصفحة
    displayReciters();
});
