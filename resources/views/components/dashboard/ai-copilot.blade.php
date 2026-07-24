<!-- AI Copilot Floating Assistant Widget -->
<style>
    @keyframes gentle-tilt {
        0%, 100% { transform: rotate(-8deg); }
        50% { transform: rotate(8deg); }
    }
    .animate-gentle-tilt {
        animation: gentle-tilt 3s ease-in-out infinite;
        transform-origin: center;
    }
</style>
<div id="ai-copilot-container" class="relative z-50 no-print" x-data="{ open: false }">
    <!-- Floating Trigger Button -->
    <button 
        @click="open = !open" 
        class="fixed bottom-6 right-6 w-16 h-20 flex items-end justify-center transition-all duration-300 focus:outline-none z-50 group select-none"
        title="Chat with Sari"
    >
        <!-- Circular Backing Background -->
        <div class="absolute bottom-0 w-14 h-14 bg-[#FF5A36] rounded-full shadow-lg shadow-[#FF5A36]/35 group-hover:scale-105 group-hover:bg-[#E04826] transition-all duration-300"></div>
        
        <!-- 1. Bottom portion clipped inside the circle -->
        <div class="absolute bottom-0 w-14 h-14 rounded-full overflow-hidden transition-all duration-300 group-hover:scale-105">
            <img 
                src="{{ asset('sari.svg') }}" 
                class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-20 h-24 max-w-none object-contain transition-all duration-300 group-hover:scale-110 group-hover:-translate-y-1.5" 
                alt="Sari"
            >
        </div>

        <!-- 2. Top portion overflowing the circle (clipped at the bottom 50% boundary) -->
        <div class="absolute bottom-0 w-14 h-14 rounded-full overflow-visible pointer-events-none transition-all duration-300 group-hover:scale-105">
            <img 
                src="{{ asset('sari.svg') }}" 
                class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-20 h-24 max-w-none object-contain transition-all duration-300 group-hover:scale-110 group-hover:-translate-y-1.5" 
                style="clip-path: inset(0 0 45% 0);" 
                alt="Sari"
            >
        </div>
    </button>

    <!-- Side Sliding Drawer -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"
        class="fixed inset-y-0 right-0 w-full sm:w-[440px] bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg shadow-2xl border-l border-slate-200/80 dark:border-slate-800/80 flex flex-col z-50"
        x-cloak
    >
        <!-- Header -->
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-[#FF5A36]/5 to-[#FF8C36]/5">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('sari.svg') }}" class="w-9 h-9 object-contain rounded-xl bg-white border border-slate-200/80 shadow-sm p-0.5" alt="Sari">
                <div>
                    <h3 class="font-bold text-slate-850 dark:text-white text-sm">Sari</h3>
                </div>
            </div>
            
            <button @click="open = false" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-250 hover:bg-slate-50 dark:hover:bg-slate-850 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Chat Area -->
        <div id="ai-chat-logs" class="flex-1 overflow-y-auto p-4 space-y-4 flex flex-col scrollbar-thin scrollbar-thumb-slate-200">
            <!-- Initial Greeting -->
            <div class="flex items-start gap-2.5 max-w-[85%]">
                <img src="{{ asset('sari.svg') }}" class="w-7.5 h-7.5 object-contain rounded-lg bg-white border border-slate-200/60 shadow-sm p-0.5 shrink-0" alt="Sari">
                <div class="bg-slate-50 dark:bg-slate-850 rounded-2xl rounded-tl-none p-3.5 border border-slate-100 dark:border-slate-800 text-xs text-slate-700 dark:text-slate-350 leading-relaxed shadow-sm">
                    <p class="font-bold text-slate-800 dark:text-white mb-1.5">Welcome! I am Sari, your AI Assistant.</p>
                    <p>I can answer questions regarding stock availability, dispatches, brands, or check uploaded spreadsheets for SKUs and stock counts.</p>
                </div>
            </div>

            <!-- Suggestion Pills (Shown in chat directly if no messages yet) -->
            <div id="ai-suggestions" class="flex flex-col gap-2 pt-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider pl-1.5">Suggested Actions</p>
                <div class="flex flex-wrap gap-2">
                    <button onclick="aiPillClick('Show me low stock items')" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-850 dark:hover:bg-slate-800 text-slate-650 dark:text-slate-300 border border-slate-200/50 dark:border-slate-800 text-[11px] font-semibold rounded-xl transition-all text-left">
                        🔍 Check Low Stock
                    </button>
                    <button onclick="aiPillClick('Summarize recent inward scans')" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-850 dark:hover:bg-slate-800 text-slate-650 dark:text-slate-300 border border-slate-200/50 dark:border-slate-800 text-[11px] font-semibold rounded-xl transition-all text-left">
                        📦 Recent Inward Activity
                    </button>
                    <button onclick="aiPillClick('What is the current active stock levels?')" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-850 dark:hover:bg-slate-800 text-slate-650 dark:text-slate-300 border border-slate-200/50 dark:border-slate-800 text-[11px] font-semibold rounded-xl transition-all text-left">
                        📊 Current Active Stock Count
                    </button>
                </div>
            </div>
        </div>

        <!-- Attachment File Display Bar -->
        <div id="ai-file-preview-bar" class="hidden px-4 py-2 bg-slate-50 dark:bg-slate-950 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#FF5A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span id="ai-file-name" class="font-bold text-slate-700 dark:text-slate-200 truncate max-w-[200px]">document.csv</span>
            </div>
            <button onclick="clearAiAttachment()" class="text-rose-500 hover:text-rose-700 text-[10px] font-bold">Remove</button>
        </div>

        <!-- Input Area -->
        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="flex gap-2 items-center">
                <!-- File Attachment Input -->
                <input 
                    type="file" 
                    id="ai-file-input" 
                    class="hidden" 
                    accept=".csv,.txt,.xlsx,.xls" 
                    onchange="handleAiFileSelect()"
                >
                <button 
                    onclick="document.getElementById('ai-file-input').click()" 
                    class="w-11 h-11 bg-slate-50 dark:bg-slate-850 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/50 dark:border-slate-800 rounded-xl flex items-center justify-center text-slate-500 hover:text-slate-700 transition-all shrink-0"
                    title="Attach Excel or CSV file"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </button>

                <!-- Input Textbox -->
                <textarea 
                    id="ai-input-message"
                    rows="1"
                    placeholder="Type a message or file query..."
                    class="flex-1 px-4 py-3 bg-slate-50 dark:bg-slate-850 border border-slate-200/60 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-[#FF5A36] focus:ring-1 focus:ring-[#FF5A36]/20 transition-all resize-none overflow-hidden"
                    onkeydown="if(event.keyCode === 13 && !event.shiftKey) { event.preventDefault(); sendAiMessage(); }"
                ></textarea>

                <!-- Standalone Send Action Button -->
                <button 
                    onclick="sendAiMessage()" 
                    class="w-11 h-11 bg-[#FF5A36] hover:bg-[#E04826] text-white rounded-xl flex items-center justify-center transition-all shrink-0 shadow-md shadow-[#FF5A36]/20"
                    title="Send Message"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Local conversation state
    let aiChatHistory = [];
    let selectedAiFile = null;

    function aiPillClick(prompt) {
        document.getElementById('ai-input-message').value = prompt;
        sendAiMessage();
    }

    function handleAiFileSelect() {
        const input = document.getElementById('ai-file-input');
        if (input.files && input.files[0]) {
            selectedAiFile = input.files[0];
            document.getElementById('ai-file-name').innerText = selectedAiFile.name;
            document.getElementById('ai-file-preview-bar').classList.remove('hidden');
        }
    }

    function clearAiAttachment() {
        selectedAiFile = null;
        document.getElementById('ai-file-input').value = '';
        document.getElementById('ai-file-preview-bar').classList.add('hidden');
    }

    function renderHtmlTable(markdownRows) {
        if (markdownRows.length < 2) return markdownRows.join('\n');
        
        let html = '<div class="overflow-x-auto my-3.5 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 shadow-sm"><table class="w-full text-left text-[11px] border-collapse bg-white dark:bg-slate-900">';
        
        let headerRow = markdownRows[0];
        let headers = headerRow.split('|').map(h => h.trim()).filter(h => h !== '');
        
        // Render Header
        html += '<thead class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200/80 dark:border-slate-800/80"><tr>';
        headers.forEach(h => {
            html += `<th class="p-2.5 font-extrabold text-slate-700 dark:text-slate-200 tracking-wider uppercase text-[10px]">${h}</th>`;
        });
        html += '</tr></thead><tbody>';
        
        // Render Data Rows
        let rowCount = 0;
        for (let i = 1; i < markdownRows.length; i++) {
            let row = markdownRows[i];
            
            // Skip separator line (like |---|---|)
            if (row.replace(/[\s|:-]/g, '') === '') {
                continue;
            }
            
            let cells = row.split('|').map(c => c.trim()).filter((c, idx, arr) => idx > 0 && idx < arr.length - 1);
            if (cells.length === 0) continue;
            
            let bgClass = rowCount % 2 === 0 ? 'bg-white dark:bg-slate-900' : 'bg-slate-50/50 dark:bg-slate-850/40';
            html += `<tr class="${bgClass} border-b border-slate-100 dark:border-slate-800/50 hover:bg-slate-100/50 dark:hover:bg-slate-800/30 transition-colors">`;
            cells.forEach(c => {
                html += `<td class="p-2.5 text-slate-650 dark:text-slate-350 font-medium">${c}</td>`;
            });
            html += '</tr>';
            rowCount++;
        }
        
        html += '</tbody></table></div>';
        return html;
    }

    function parseMarkdown(text) {
        // Strip out reasoning outputs or thinking blocks if returned by thinking models
        let cleanText = text.replace(/<thinking>[\s\S]*?<\/thinking>/gim, '').trim();

        // Safe sanitization
        let formatted = cleanText
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // Process Markdown Tables
        let lines = formatted.split('\n');
        let inTable = false;
        let tableRows = [];
        let outputLines = [];

        for (let i = 0; i < lines.length; i++) {
            let line = lines[i].trim();
            
            // Check if line looks like a table row (starts and ends with | or contains multiple |)
            if (line.startsWith('|') && line.endsWith('|')) {
                if (!inTable) {
                    inTable = true;
                    tableRows = [];
                }
                tableRows.push(line);
            } else {
                if (inTable) {
                    // Flush the table
                    outputLines.push(renderHtmlTable(tableRows));
                    inTable = false;
                }
                outputLines.push(lines[i]);
            }
        }
        if (inTable) {
            outputLines.push(renderHtmlTable(tableRows));
        }
        
        formatted = outputLines.join('\n');

        // Headers
        formatted = formatted.replace(/^### (.*$)/gim, '<h4 class="font-extrabold text-slate-800 dark:text-white mt-3 mb-1 text-xs uppercase tracking-wider">$1</h4>');
        formatted = formatted.replace(/^## (.*$)/gim, '<h3 class="font-bold text-slate-900 dark:text-white mt-4 mb-2 text-sm">$1</h3>');
        
        // Bold
        formatted = formatted.replace(/\*\*(.*?)\*\"/g, '<strong class="font-bold text-slate-900 dark:text-white">$1</strong>');
        formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-slate-900 dark:text-white">$1</strong>');
        
        // Code Blocks
        formatted = formatted.replace(/```php([\s\S]*?)```/g, '<pre class="bg-slate-950 text-emerald-400 p-2.5 rounded-lg text-[10px] font-mono my-2 overflow-x-auto">$1</pre>');
        formatted = formatted.replace(/```env([\s\S]*?)```/g, '<pre class="bg-slate-950 text-[#FF5A36] p-2.5 rounded-lg text-[10px] font-mono my-2 overflow-x-auto">$1</pre>');
        formatted = formatted.replace(/```([\s\S]*?)```/g, '<pre class="bg-slate-950 text-slate-300 p-2.5 rounded-lg text-[10px] font-mono my-2 overflow-x-auto">$1</pre>');
        
        // Bullet Lists
        formatted = formatted.replace(/^\s*-\s+(.*)/gim, '<li class="ml-4 list-disc pl-1 text-[11px]">$1</li>');
        
        // Horizontal Rule
        formatted = formatted.replace(/---/g, '<hr class="border-slate-200 dark:border-slate-800 my-2" />');

        // Line Breaks
        return formatted.replace(/\n/g, '<br>');
    }

    function appendChatMessage(role, content) {
        const container = document.getElementById('ai-chat-logs');
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex items-start gap-2.5 max-w-[85%] ${role === 'user' ? 'self-end flex-row-reverse' : ''}`;

        const iconContainer = document.createElement('div');
        if (role === 'user') {
            iconContainer.className = 'w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center shrink-0';
            iconContainer.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>`;
        } else {
            iconContainer.className = 'w-7 h-7 rounded-lg bg-white border border-slate-200/85 text-[#FF5A36] flex items-center justify-center shrink-0 p-0.5 shadow-sm';
            iconContainer.innerHTML = `<img src="/sari.svg" class="w-full h-full object-contain rounded" alt="Sari">`;
        }

        const bubble = document.createElement('div');
        bubble.className = `${role === 'user' ? 'bg-[#FF5A36] text-white rounded-tr-none' : 'bg-slate-50 dark:bg-slate-850 border border-slate-100 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-tl-none'} rounded-2xl p-3.5 text-xs leading-relaxed shadow-sm`;
        bubble.innerHTML = parseMarkdown(content);

        messageDiv.appendChild(iconContainer);
        messageDiv.appendChild(bubble);
        container.appendChild(messageDiv);
        container.scrollTop = container.scrollHeight;
    }

    function appendLoadingIndicator() {
        const container = document.getElementById('ai-chat-logs');
        const loaderDiv = document.createElement('div');
        loaderDiv.id = 'ai-chat-loader';
        loaderDiv.className = 'flex items-start gap-2.5 max-w-[85%]';
        loaderDiv.innerHTML = `
            <div class="w-7 h-7 rounded-lg bg-white border border-slate-200/85 text-[#FF5A36] flex items-center justify-center shrink-0 p-0.5 shadow-sm">
                <img src="/sari.svg" class="w-full h-full object-contain rounded" alt="Sari">
            </div>
            <div class="bg-slate-50 dark:bg-slate-850 rounded-2xl rounded-tl-none p-3.5 border border-slate-100 dark:border-slate-800 text-xs text-slate-400 flex items-center gap-1">
                <span class="animate-bounce">●</span>
                <span class="animate-bounce" style="animation-delay: 0.2s">●</span>
                <span class="animate-bounce" style="animation-delay: 0.4s">●</span>
            </div>
        `;
        container.appendChild(loaderDiv);
        container.scrollTop = container.scrollHeight;
    }

    function removeLoadingIndicator() {
        const loader = document.getElementById('ai-chat-loader');
        if (loader) {
            loader.remove();
        }
    }

    function sendAiMessage() {
        const inputField = document.getElementById('ai-input-message');
        const text = inputField.value.trim();
        
        if (!text && !selectedAiFile) return;

        // Hide suggestions on first send
        const sug = document.getElementById('ai-suggestions');
        if (sug) sug.remove();

        if (selectedAiFile) {
            // Document query upload pathway
            const file = selectedAiFile;
            appendChatMessage('user', `📄 Uploaded file: **${file.name}**\n\nPrompt: ${text || 'Analyze this file.'}`);
            inputField.value = '';
            clearAiAttachment();
            appendLoadingIndicator();

            const formData = new FormData();
            formData.append('file', file);
            formData.append('instruction', text);

            fetch('/ai/analyze-file', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                removeLoadingIndicator();
                if (data.response) {
                    appendChatMessage('assistant', data.response);
                    aiChatHistory.push({ role: 'user', content: `[Sheet File Attached: ${data.fileName}] ${text}` });
                    aiChatHistory.push({ role: 'assistant', content: data.response });
                } else {
                    appendChatMessage('assistant', "⚠️ I encountered an error while parsing your spreadsheet file. Please check that it is a valid format.");
                }
            })
            .catch(err => {
                removeLoadingIndicator();
                appendChatMessage('assistant', "❌ Connection timeout or service error occurred. Please make sure the server is serving correct endpoints.");
                console.error(err);
            });

        } else {
            // General natural language chat pathway
            appendChatMessage('user', text);
            inputField.value = '';
            appendLoadingIndicator();

            fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: text,
                    history: aiChatHistory
                })
            })
            .then(res => res.json())
            .then(data => {
                removeLoadingIndicator();
                if (data.response) {
                    appendChatMessage('assistant', data.response);
                    aiChatHistory.push({ role: 'user', content: text });
                    aiChatHistory.push({ role: 'assistant', content: data.response });
                    // Keep history brief to optimize tokens
                    if (aiChatHistory.length > 10) {
                        aiChatHistory = aiChatHistory.slice(-10);
                    }
                } else {
                    appendChatMessage('assistant', "⚠️ Received empty response or API error.");
                }
            })
            .catch(err => {
                removeLoadingIndicator();
                appendChatMessage('assistant', "❌ A connection error occurred. Please verify backend state.");
                console.error(err);
            });
        }
    }
</script>
