import React, { useRef } from 'react';
import { Printer, Download, X, FileText, CheckCircle2 } from 'lucide-react';
import jsPDF from 'jspdf';
import html2canvas from 'html2canvas';

interface OfficialDocumentModalProps {
  title: string;
  memoNumber?: string;
  dateStr?: string;
  recipient?: string;
  subject?: string;
  bodyContent: React.ReactNode;
  authorityName?: string;
  authorityPosition?: string;
  onClose: () => void;
}

export const OfficialDocumentModal: React.FC<OfficialDocumentModalProps> = ({
  title,
  memoNumber = 'MEMO-UNERG-VA-2024-000',
  dateStr,
  recipient,
  subject,
  bodyContent,
  authorityName = 'Dra. María Elena Rodríguez',
  authorityPosition = 'Vicerrectora Académica (E) - UNERG',
  onClose,
}) => {
  const documentRef = useRef<HTMLDivElement>(null);
  const formattedDate = dateStr || new Date().toLocaleDateString('es-VE', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });

  const handlePrint = () => {
    window.print();
  };

  const handleDownloadPdf = async () => {
    if (!documentRef.current) return;
    try {
      const canvas = await html2canvas(documentRef.current, {
        scale: 2,
        useCORS: true,
        logging: false,
      });
      const imgData = canvas.toDataURL('image/png');
      const pdf = new jsPDF('p', 'mm', 'a4');
      const pdfWidth = pdf.internal.pageSize.getWidth();
      const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
      pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
      pdf.save(`${memoNumber}_${title.toLowerCase().replace(/\s+/g, '_')}.pdf`);
    } catch (err) {
      console.error('Error generating PDF', err);
      // Fallback to native print
      window.print();
    }
  };

  return (
    <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
      <div className="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[92vh] flex flex-col overflow-hidden border border-slate-300">
        {/* Modal Top Control Bar (Hidden when printing) */}
        <div className="bg-[#003366] text-white px-6 py-3.5 flex items-center justify-between print:hidden border-b border-amber-500">
          <div className="flex items-center space-x-2">
            <FileText className="w-5 h-5 text-amber-400" />
            <h3 className="font-bold text-sm sm:text-base">Documento Oficial UNERG: {title}</h3>
          </div>
          <div className="flex items-center space-x-2">
            <button
              onClick={handlePrint}
              className="bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center space-x-1.5 transition-colors border border-white/20"
            >
              <Printer className="w-4 h-4" />
              <span>Imprimir</span>
            </button>
            <button
              onClick={handleDownloadPdf}
              className="bg-amber-500 hover:bg-amber-600 text-slate-950 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center space-x-1.5 transition-colors shadow-sm"
            >
              <Download className="w-4 h-4" />
              <span>Descargar PDF</span>
            </button>
            <button
              onClick={onClose}
              className="text-slate-300 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors"
            >
              <X className="w-5 h-5" />
            </button>
          </div>
        </div>

        {/* Scrollable Printable Document Container */}
        <div className="flex-1 overflow-y-auto p-4 sm:p-8 bg-slate-100 flex justify-center">
          <div
            ref={documentRef}
            id="printable-area"
            className="bg-white w-full max-w-[210mm] min-h-[297mm] p-8 sm:p-12 shadow-md border border-slate-300 text-slate-900 font-serif flex flex-col justify-between"
          >
            {/* Header section matching blade view */}
            <div>
              <div className="border-b-2 border-[#003366] pb-4 mb-6 text-center relative">
                <div className="flex items-center justify-center space-x-4 mb-2">
                  <div className="w-16 h-16 flex items-center justify-center">
                    <img 
                      src="/images/LogoUnerg.png" 
                      alt="Logo UNERG" 
                      className="max-h-16 object-contain"
                      onError={(e) => {
                        (e.target as HTMLElement).style.display = 'none';
                      }}
                    />
                  </div>
                  <div>
                    <h2 className="text-sm sm:text-base font-bold text-[#003366] uppercase tracking-wide leading-tight">
                      República Bolivariana de Venezuela<br />
                      Universidad Nacional Experimental de los Llanos Centrales<br />
                      "Rómulo Gallegos"<br />
                      <span className="text-xs font-semibold text-slate-700">Vicerrectorado Académico • Dirección de Asuntos Profesorales</span>
                    </h2>
                  </div>
                </div>
                <p className="text-[10px] text-slate-500 font-sans">
                  Sede Central: Av. Principal de San Juan de los Morros, Estado Guárico • Teléfono: (0246) 431.83.00
                </p>
              </div>

              {/* Document Meta / Memo Header */}
              <div className="flex justify-between items-start text-xs font-sans text-slate-700 mb-6 pb-3 border-b border-slate-200">
                <div>
                  <p><strong className="text-slate-900">Nº de Control:</strong> <span className="font-mono text-[#003366] font-bold">{memoNumber}</span></p>
                  {recipient && <p className="mt-1"><strong className="text-slate-900">Para:</strong> {recipient}</p>}
                  {subject && <p className="mt-1"><strong className="text-slate-900">Asunto:</strong> {subject}</p>}
                </div>
                <div className="text-right">
                  <p><strong className="text-slate-900">Lugar y Fecha:</strong></p>
                  <p>San Juan de los Morros, {formattedDate}</p>
                </div>
              </div>

              {/* Document Title */}
              <div className="text-center my-6">
                <h1 className="text-lg font-bold text-[#003366] tracking-wide uppercase underline decoration-amber-500 decoration-2 underline-offset-4">
                  {title}
                </h1>
              </div>

              {/* Main Body Content */}
              <div className="text-sm leading-relaxed text-slate-800 space-y-4 font-sans text-justify">
                {bodyContent}
              </div>
            </div>

            {/* Signature & Seal Block */}
            <div className="mt-12 pt-6 font-sans">
              <div className="grid grid-cols-2 gap-8 text-center text-xs">
                <div>
                  <div className="h-16 flex items-end justify-center mb-1">
                    <div className="w-32 border-b border-slate-400"></div>
                  </div>
                  <p className="font-bold text-slate-900">{authorityName}</p>
                  <p className="text-slate-600">{authorityPosition}</p>
                  <p className="text-[10px] text-slate-400">Firma y Sello Autorizado</p>
                </div>
                <div>
                  <div className="h-16 flex items-center justify-center mb-1">
                    <div className="w-24 h-24 border border-dashed border-amber-600/40 rounded-full flex items-center justify-center p-1 text-[9px] text-amber-900 font-bold uppercase text-center rotate-[-12deg] bg-amber-50/50">
                      Sello Oficial UNERG<br />Vicerrectorado Académico
                    </div>
                  </div>
                  <p className="font-bold text-slate-900">Comisión Calificadora Docente</p>
                  <p className="text-slate-600">Dirección de Control y Seguimiento</p>
                </div>
              </div>

              {/* Footer Notice */}
              <div className="border-t border-slate-200 mt-8 pt-2 text-[9px] text-slate-400 text-center font-sans">
                SIGEDOR: Sistema para Gestión de Docentes Ordinarios y Reportes • Generado el {new Date().toLocaleString('es-VE')} (Hora de Venezuela)
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
