document.addEventListener('DOMContentLoaded', () => {
    // --- DOM 元素獲取 ---
    const homeView = document.getElementById('home-view');
    const optimizeView = document.getElementById('optimize-view');
    const pathConfigArea = document.getElementById('path-config-area');
    const pathMessage = document.getElementById('path-message');
    const selectPathButton = document.getElementById('select-path-button');
    const resetPathButton = document.getElementById('reset-path-button');
    
    // 主畫面元素
    const launchPlusCard = document.getElementById('launch-plus-card');
    const launchPlusUpCard = document.getElementById('launch-plusup-card');
    const installOptimizationsCard = document.getElementById('install-optimizations-card');
    const goToOptimizeViewButton = document.getElementById('go-to-optimize-view');
    const homeGrid = document.querySelector('.home-grid');

    // 優化畫面元素
    const backToHomeButton = document.getElementById('back-to-home-button');
    const cardGrid = document.getElementById('card-grid');
    const cardTemplate = document.getElementById('card-template');
    const previewPanel = document.getElementById('preview-panel');
    const previewImage = document.getElementById('preview-image');
    const modeRadios = document.querySelectorAll('input[name="mode"]');
    const pathDisplay = document.getElementById('target-path-display');
    const tabContainer = document.querySelector('.tabs');

    // 解析度調整畫面元素
    const resolutionView = document.getElementById('resolution-view');
    const goToResolutionViewButton = document.getElementById('go-to-resolution-view');
    const closeResolutionViewButton = document.getElementById('close-resolution-view');
    const saveResolutionButton = document.getElementById('save-resolution-button');
    const resolutionSelectGroup = document.getElementById('resolution-select-group');
    const resolutionSelect = document.getElementById('resolution-select');
    const winModeRadios = document.querySelectorAll('input[name="winMode"]');

    // --- 應用程式狀態 ---
    const state = {
        get mode() { return document.querySelector('input[name="mode"]:checked').value; },
        currentCategory: 'room',
        items: [],
        statuses: {},
        customPath: '',
        defaultPathExists: false,
        plusExists: false,
        plusUpExists: false,
    };

    // --- UI 輔助函式 ---
    const showView = (viewId) => {
        homeView.style.display = 'none';
        optimizeView.style.display = 'none';
        document.getElementById(viewId).style.display = 'block';
    };

    const updateTargetPathDisplay = () => {
        const hasPath = state.customPath || state.defaultPathExists;
        let displayPath = '目標路徑: 尚未設定';
        if (hasPath) {
            const subDir = state.mode === 'plus' ? 'Plus\\edata' : 'PlusUP\\edata';
            const basePath = state.customPath ? state.customPath : '(使用預設位置)';
            displayPath = `目標路徑: ${basePath}\\${subDir}`;
        }
        pathDisplay.textContent = displayPath;
        pathDisplay.title = displayPath;
    };

    const updateUIState = () => {
        const hasAnyPath = state.customPath || state.defaultPathExists;
        const canDoAnything = state.plusExists || state.plusUpExists;

        launchPlusCard.style.display = state.plusExists ? 'flex' : 'none';
        launchPlusUpCard.style.display = state.plusUpExists ? 'flex' : 'none';
        installOptimizationsCard.style.display = canDoAnything ? 'flex' : 'none';

        if (!canDoAnything && !hasAnyPath) {
            pathConfigArea.style.display = 'flex';
            pathMessage.textContent = '【找不到 TWLoader 安裝資料夾，請手動選擇主資料夾位置。】';
            selectPathButton.style.display = 'inline-block';
            resetPathButton.style.display = 'none';
        } else if (state.customPath) {
             pathConfigArea.style.display = 'flex';
             pathMessage.textContent = `目前使用自訂路徑：${state.customPath}`;
             selectPathButton.style.display = 'none';
             resetPathButton.style.display = 'inline-block';
        } else {
            pathConfigArea.style.display = 'none';
        }
        updateTargetPathDisplay();
    };

    const setButtonState = (button, installing) => {
        if (!button) return;
        const btnText = button.querySelector('.btn-text');
        const spinner = button.querySelector('.spinner');
        button.classList.toggle('installing', installing);
        button.disabled = installing;
        if (btnText) btnText.classList.toggle('hidden', installing);
        if (spinner) spinner.style.display = installing ? 'block' : 'none';
    };

    const showToast = (message, type = 'info', duration = 5000) => {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        toastContainer.appendChild(toast);
        if (duration > 0) {
            setTimeout(() => toast.remove(), duration);
        }
        return toast;
    };

    const handlePermissionError = (errorMessage) => {
        if (confirm(`${errorMessage}\n\n是否以系統管理員身分重啟程式？`)) {
            relaunchAsAdmin();
        }
    };

    const relaunchAsAdmin = async () => {
        try {
            showToast('正在請求管理員權限...', 'info');
            await fetch('/api/relaunch-admin', { method: 'POST' });
        } catch (error) {
            showToast(`無法重啟: ${error.message || '未知錯誤'}`, 'error');
        }
    };
    
    // --- 主要邏輯 ---

    const fetchAndRenderItems = async (category) => {
        cardGrid.innerHTML = '<div class="spinner-center"></div>';
        try {
            const response = await fetch(`/api/items/${category}`);
            if (!response.ok) throw new Error(`伺服器錯誤: ${response.statusText}`);
            state.items = await response.json() || [];
            state.currentCategory = category;
            renderCards();
            await updateFileStatuses();
        } catch (error) {
            cardGrid.innerHTML = `<p class="error-message">無法載入「${category}」項目清單。</p>`;
        }
    };

    const renderCards = () => {
        cardGrid.innerHTML = '';
        if (!state.items || state.items.length === 0) {
            cardGrid.innerHTML = `<p class="info-message">此類別目前沒有可用的項目。</p>`;
            return;
        }
        state.items.forEach(item => {
            const cardClone = cardTemplate.content.cloneNode(true);
            const cardElement = cardClone.querySelector('.card');
            cardElement.dataset.slug = item.slug;
            cardElement.dataset.imageUrl = item.imageURL;
            cardClone.querySelector('.card-thumb').src = item.imageURL;
            cardClone.querySelector('.card-thumb').alt = item.name;
            cardClone.querySelector('.card-title').textContent = item.name;
            cardClone.querySelector('.install-button').dataset.slug = item.slug;
            cardClone.querySelector('.uninstall-button').dataset.slug = item.slug;
            cardGrid.appendChild(cardClone);
        });
    };

    const updateFileStatuses = async () => {
        const hasPath = state.customPath || state.defaultPathExists;
        if (!state.items.length || !hasPath) {
            state.statuses = {};
            updateAllCardButtons();
            return;
        }
        const filesToCheck = [...new Set(state.items.map(item => item.targetFile))];
        try {
            const response = await fetch('/api/status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mode: state.mode, files: filesToCheck, customPath: state.customPath })
            });
            if (!response.ok) throw new Error('無法獲取檔案狀態');
            const data = await response.json();
            state.statuses = data.exists || {};
            updateAllCardButtons();
        } catch (error) {
            console.error('更新檔案狀態失敗:', error);
        }
    };

    const updateAllCardButtons = () => {
        cardGrid.querySelectorAll('.card').forEach(card => {
            const slug = card.dataset.slug;
            const item = state.items.find(i => i.slug === slug);
            if (!item) return;
            const isInstalled = state.statuses[item.targetFile] || false;
            card.querySelector('.install-button').style.display = isInstalled ? 'none' : 'flex';
            card.querySelector('.uninstall-button').style.display = isInstalled ? 'flex' : 'none';
        });
    };
    
    // --- API 處理器 ---
    const handleInstallClick = createApiRequestHandler('/api/install', '安裝', true);
    const handleUninstallClick = createApiRequestHandler('/api/uninstall', '移除', true);

    function createApiRequestHandler(endpoint, actionText, needsConfirm) {
        return async (slug, button) => {
            const itemName = state.items.find(i => i.slug === slug)?.name || slug;
            if (needsConfirm && !confirm(`您確定要${actionText}「${itemName}」嗎？`)) return;

            setButtonState(button, true);
            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ slug, mode: state.mode, category: state.currentCategory, customPath: state.customPath })
                });
                const data = await response.json();
                if (data.ok) {
                    showToast(`「${itemName}」${actionText}成功！`, 'success');
                    await updateFileStatuses();
                } else if (data.needAdmin) {
                    handlePermissionError(data.error);
                } else {
                    showToast(`${actionText}失敗: ${data.error || '未知錯誤'}`, 'error');
                }
            } catch (error) {
                showToast('請求失敗，請檢查網路連線或後端服務。', 'error');
            } finally {
                setButtonState(button, false);
            }
        };
    }

    // --- 遊戲內容更新 ---
    const handleCheckUpdates = async (mode) => {
        showToast(`正在為 ${mode.toUpperCase()} 模式檢查更新...`, 'info');
        try {
            const res = await fetch('/api/check-updates', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mode, customPath: state.customPath })
            });
            const data = await res.json();
            if (!data.ok) throw new Error(data.error || '檢查更新失敗');

            if (data.updateNeeded) {
                showToast(`發現 ${data.items.length} 個更新項目，開始下載...`, 'info');
                await handleApplyUpdates(mode, data.items);
            } else {
                showToast(`${mode.toUpperCase()} 模式已是最新版本`, 'success');
            }
        } catch (error) {
            showToast(`檢查 ${mode.toUpperCase()} 更新時發生錯誤: ${error.message}`, 'error');
        }
    };

    const handleApplyUpdates = async (mode, items) => {
        try {
             const res = await fetch('/api/apply-updates', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mode, customPath: state.customPath, items })
            });
            const data = await res.json();
             if (data.needAdmin) {
                return handlePermissionError(data.error);
            }
            if (data.failed && data.failed.length > 0) {
                const failedFiles = data.failed.map(f => f.path).join(', ');
                showToast(`部分檔案更新失敗: ${failedFiles}`, 'error');
            } else if (!data.ok) {
                 throw new Error(data.error || '更新失敗');
            } else {
                showToast(data.message || '更新完成', 'success');
            }
        } catch (error) {
            showToast(`套用更新時發生錯誤: ${error.message}`, 'error');
        }
    };
    
    // --- 遊戲主程式更新邏輯 ---
    const checkForGameUpdate = async () => {
        try {
            const res = await fetch('/api/game-update-status');
            const data = await res.json();
            if (data.updateNeeded) {
                showGameUpdateNotification();
            }
        } catch (error) {
            console.error('無法檢查遊戲主程式更新:', error);
        }
    };

    const showGameUpdateNotification = () => {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast warning persistent';

        const message = document.createElement('span');
        message.textContent = '偵測到主程式需要更新！';
        toast.appendChild(message);

        const updateButton = document.createElement('button');
        updateButton.textContent = '立即更新';
        updateButton.onclick = async () => {
            showToast('正在啟動更新程式...', 'info');
            try {
                const res = await fetch('/api/run-game-patcher', { method: 'POST' });
                const data = await res.json();
                if (data.ok) {
                    showToast('更新程式已啟動！', 'success');
                    toast.remove();
                } else {
                    throw new Error(data.error || '未知錯誤');
                }
            } catch (err) {
                showToast(`啟動更新程式失敗: ${err.message}`, 'error');
            }
        };
        toast.appendChild(updateButton);
        toastContainer.appendChild(toast);
    };

    // --- 應用程式自我更新的完整邏輯 ---
    const checkForAppUpdate = async () => {
        try {
            const res = await fetch('/api/check-app-update');
            const data = await res.json();
            if (data.updateAvailable) {
                showAppUpdateNotification(data);
            }
        } catch (error) {
            console.error('無法檢查應用程式更新:', error);
        }
    };

    const showAppUpdateNotification = (updateInfo) => {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast info persistent';

        const message = document.createElement('div');
        message.style.display = 'flex';
        message.style.flexDirection = 'column';
        message.style.alignItems = 'flex-start';

        const title = document.createElement('strong');
        title.textContent = `發現新版本: ${updateInfo.latestVersion.version}`;
        
        const notes = document.createElement('p');
        notes.textContent = `更新說明: ${updateInfo.latestVersion.notes || '無'}`;
        notes.style.margin = '5px 0 0 0';
        notes.style.fontSize = '0.9em';

        message.appendChild(title);
        message.appendChild(notes);
        toast.appendChild(message);

        const updateButton = document.createElement('button');
        updateButton.textContent = '立即更新';
        updateButton.onclick = () => applyAppUpdate(toast, updateInfo.latestVersion);
        
        toast.appendChild(updateButton);
        toastContainer.appendChild(toast);
    };

    const applyAppUpdate = async (toastElement, versionInfo) => {
        toastElement.querySelector('button').disabled = true;
        showToast('正在準備更新，請稍候...', 'info', 0);

        try {
            const res = await fetch('/api/apply-app-update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(versionInfo)
            });
            const data = await res.json();
            if (data.ok) {
                showToast('更新程式已啟動，本工具即將關閉。', 'success');
            } else {
                throw new Error(data.error || '未知錯誤');
            }
        } catch(err) {
            showToast(`更新失敗: ${err.message}`, 'error');
            toastElement.querySelector('button').disabled = false;
        }
    };
    
    // --- 事件處理器 ---

    // 視圖切換
    goToOptimizeViewButton.addEventListener('click', () => {
        showView('optimize-view');
        const hasPath = state.customPath || state.defaultPathExists;
        if(hasPath) {
            fetchAndRenderItems(state.currentCategory);
        }
    });
    backToHomeButton.addEventListener('click', () => showView('home-view'));

    // =========================================================
    // 【已修改】: 解析度調整 Modal 的事件處理
    // =========================================================

    // 負責根據顯示模式來鎖定/解鎖解析度選單的函式
    const updateResolutionLock = () => {
        const selectedMode = document.querySelector('input[name="winMode"]:checked').value;
        
        // 檢查是否為全螢幕(0)或全螢幕4:3(2)模式
        if (selectedMode === '0' || selectedMode === '2') {
            resolutionSelect.value = '1024x768'; // 強制設定為 1024x768
            resolutionSelect.disabled = true;     // 禁用下拉選單
            resolutionSelectGroup.style.opacity = '0.5'; // 視覺上變灰，提示用戶不可選
        } else { // 否則就是視窗化模式
            resolutionSelect.disabled = false;    // 啟用下拉選單
            resolutionSelectGroup.style.opacity = '1';  // 恢復正常外觀
        }
    };

    // 為所有「顯示模式」選項按鈕綁定 change 事件
    winModeRadios.forEach(radio => radio.addEventListener('change', updateResolutionLock));

    // 當點擊「調整解析度」按鈕時
    goToResolutionViewButton.addEventListener('click', async () => {
        try {
            const response = await fetch('/api/resolution-config');
            if (!response.ok) throw new Error('無法獲取當前解析度設定');
            const config = await response.json();

            document.querySelector(`input[name="winMode"][value="${config.winMode}"]`).checked = true;
            const currentResValue = `${config.width}x${config.height}`;
            if (resolutionSelect.querySelector(`option[value="${currentResValue}"]`)) {
                resolutionSelect.value = currentResValue;
            } else {
                const newOption = new Option(`${config.width} x ${config.height}`, currentResValue, true, true);
                resolutionSelect.add(newOption);
            }

            // 打開視窗時，立即執行一次鎖定狀態檢查
            updateResolutionLock(); 

            resolutionView.style.display = 'flex';

        } catch (error) {
            showToast(`錯誤: ${error.message}`, 'error');
        }
    });

    // 當點擊 Modal 中的「關閉」按鈕時
    closeResolutionViewButton.addEventListener('click', () => {
        resolutionView.style.display = 'none';
    });

    // 當點擊 Modal 外部的灰色遮罩時，也關閉視窗
    resolutionView.addEventListener('click', (e) => {
        if (e.target === resolutionView) {
            resolutionView.style.display = 'none';
        }
    });

    // 當點擊「儲存設定」按鈕時 (此部分無需修改)
    saveResolutionButton.addEventListener('click', async () => {
        const selectedMode = document.querySelector('input[name="winMode"]:checked').value;
        const [width, height] = resolutionSelect.value.split('x');

        const newConfig = {
            winMode: parseInt(selectedMode, 10),
            width: parseInt(width, 10),
            height: parseInt(height, 10),
        };

        try {
            const response = await fetch('/api/resolution-config', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(newConfig)
            });

            const data = await response.json();

            if (data.ok) {
                showToast('解析度設定已成功儲存！', 'success');
                resolutionView.style.display = 'none';
            } else if (data.needAdmin) {
                handlePermissionError(data.error);
            } else {
                throw new Error(data.error || '未知錯誤');
            }
        } catch (error) {
            showToast(`儲存失敗: ${error.message}`, 'error');
        }
    });

    // 主畫面按鈕
    homeGrid.addEventListener('click', async (e) => {
        const launchBtn = e.target.closest('.launch-button');
        if (launchBtn) {
            const mode = launchBtn.dataset.mode;
            showToast(`正在發出 ${mode.toUpperCase()} 模式啟動命令...`, 'info');
            try {
                const res = await fetch(`/api/launch/${mode}`, { method: 'POST' });
                const data = await res.json();
                if (!data.ok) {
                   throw new Error(data.error);
                }
                showToast('已發出啟動命令。', 'success');
            } catch(err) {
                showToast(`啟動失敗: ${err.message}`, 'error');
            }
        }

        const updateBtn = e.target.closest('.update-button');
        if (updateBtn) {
            handleCheckUpdates(updateBtn.dataset.mode);
        }
    });

    // 路徑設定
    selectPathButton.addEventListener('click', async () => {
        try {
            const response = await fetch('/api/select-path', { method: 'POST' });
            const data = await response.json();
            if (data.path) {
                state.customPath = data.path;
                showToast(`基礎路徑已更新為：${data.path}`, 'success');
                await init();
            }
        } catch (error) {
            showToast(`選擇路徑失敗: ${error.message || '未知錯誤'}`, 'error');
        }
    });

    resetPathButton.addEventListener('click', async () => {
        if (!confirm('您確定要重設自訂路徑嗎？')) return;
        try {
            await fetch('/api/reset-path', { method: 'POST' });
            showToast('自訂路徑已重設', 'success');
            await init();
        } catch (error) {
            showToast(`重設路徑失敗: ${error.message || '未知錯誤'}`, 'error');
        }
    });

    // 優化畫面事件
    tabContainer.addEventListener('click', (e) => {
        const targetTab = e.target.closest('.tab-link');
        if (!targetTab || targetTab.disabled || targetTab.classList.contains('active')) return;
        tabContainer.querySelector('.active').classList.remove('active');
        targetTab.classList.add('active');
        fetchAndRenderItems(targetTab.dataset.category);
    });

    cardGrid.addEventListener('click', (e) => {
        const installBtn = e.target.closest('.install-button:not(.installing)');
        if (installBtn) handleInstallClick(installBtn.dataset.slug, installBtn);

        const uninstallBtn = e.target.closest('.uninstall-button:not(.installing)');
        if (uninstallBtn) handleUninstallClick(uninstallBtn.dataset.slug, uninstallBtn);
    });

    modeRadios.forEach(radio => radio.addEventListener('change', () => {
        updateTargetPathDisplay();
        updateFileStatuses();
    }));
    
    // 預覽圖
    cardGrid.addEventListener('mousemove', e => {
        const card = e.target.closest('.card');
        if (card?.dataset.imageUrl) {
            previewPanel.style.display = 'block';
            previewImage.src = card.dataset.imageUrl;
            const offsetX = 15, offsetY = 15;
            let left = e.clientX + offsetX;
            if (left + previewPanel.offsetWidth > window.innerWidth) {
                left = e.clientX - previewPanel.offsetWidth - offsetX;
            }
            previewPanel.style.left = `${left}px`;
            previewPanel.style.top = `${e.clientY + offsetY}px`;
        } else {
            previewPanel.style.display = 'none';
        }
    });
    cardGrid.addEventListener('mouseout', () => {
        previewPanel.style.display = 'none';
    });

    // --- 應用程式初始化 ---
    const init = async () => {
        try {
            const response = await fetch('/api/get-initial-state');
            if (!response.ok) throw new Error('無法獲取初始狀態');
            const initialState = await response.json();
            
            state.customPath = initialState.customPath;
            state.defaultPathExists = initialState.defaultPathExists;
            state.plusExists = initialState.plusExists;
            state.plusUpExists = initialState.plusUpExists;

            const hasPath = initialState.customPath || initialState.defaultPathExists;
            if (hasPath) {
                if (initialState.plusExists) handleCheckUpdates('plus');
                if (initialState.plusUpExists) handleCheckUpdates('plusup');
            }

            updateUIState();
            
            // 同時檢查遊戲更新與應用程式更新
            checkForGameUpdate();
            checkForAppUpdate();
            
            const initialCategory = tabContainer.querySelector('.active')?.dataset.category || 'room';
            await fetchAndRenderItems(initialCategory);

        } catch (error) {
            console.error("初始化失敗:", error);
            pathDisplay.textContent = '錯誤: 無法連接後端服務';
            cardGrid.innerHTML = `<p class="error-message">應用程式初始化失敗，請確認後端服務是否已啟動。</p>`;
            showToast(`初始化失敗: ${error.message || '未知錯誤'}`, "error");
        }
    };

    init();
    showView('home-view');

    // --- WebSocket 連線邏輯 ---
    function setupWebSocket() {
        const socket = new WebSocket('ws://127.0.0.1:8787/ws');
        socket.onopen = () => {
            console.log('WebSocket connection established.');
        };
        socket.onclose = () => {
            console.log('WebSocket connection closed. Attempting to reconnect in 3 seconds...');
            setTimeout(setupWebSocket, 3000);
        };
        socket.onerror = (error) => {
            console.error('WebSocket Error. Check if the backend server is running.', error);
        };
    }
    setupWebSocket();
});