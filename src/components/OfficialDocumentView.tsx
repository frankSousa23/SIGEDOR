import React from 'react';
import { Report, Teacher } from '../types';
import { useData } from '../context/DataContext';
import { Printer, Download, ArrowLeft, ShieldCheck, QrCode } from 'lucide-react';

interface OfficialDocumentViewProps {
  report: Report;
  onBack: () => void;
}

export const OfficialDocumentView: React.FC<OfficialDocumentViewProps> = ({ report, onBack }) => {
  const { teachers, categories, dedications, sites } = useData();

  const teacher = teachers.find(t => t.cdi === report.teacher_cdi);
  const category = categories.find(c => c.teacher_cdi === report.teacher_cdi);
  const dedication = dedications.find(d => d.teacher_cdi === report.teacher_cdi);
  const teacherSites = sites.filter(s => s.teacher_cdi === report.teacher_cdi);

  const currentDate = new Date().toLocaleDateString('es-VE', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });

  const handlePrint = () => {
    window.print();
  };

  return (
    <div className="space-y-4">
      {/* Control Action Bar */}
      <div className="no-print flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <button
          onClick={onBack}
          className="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
        >
          <ArrowLeft className="w-4 h-4" /> Volver al listado
        </button>

        <div className="flex items-center gap-2">
          <span className="text-xs text-slate-500 hidden sm:inline">
            Código de Verificación: <strong className="font-mono text-slate-800">{report.verification_code}</strong>
          </span>
          <button
            onClick={handlePrint}
            className="flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-unerg-blue hover:bg-unerg-blue-light rounded-lg shadow-sm transition-all"
          >
            <Printer className="w-4 h-4" /> Imprimir / Guardar como PDF
          </button>
        </div>
      </div>

      {/* Printable Sheet (Simulated A4 Official Paper) */}
      <div className="bg-white mx-auto max-w-4xl p-8 sm:p-12 rounded-xl border border-slate-300 shadow-lg text-slate-900 print:border-none print:shadow-none print:p-0">
        {/* Official Header */}
        <div className="border-b-2 border-unerg-blue pb-4 mb-6">
          <div className="flex items-center justify-between gap-4">
            <div className="w-20 h-20 flex-shrink-0 flex items-center justify-center">
              <img
                src="/images/LogoUnerg.png"
                alt="Logo UNERG"
                className="w-full h-full object-contain"
                onError={(e) => {
                  (e.currentTarget as HTMLElement).style.display = 'none';
                }}
              />
            </div>
            <div className="text-center flex-1">
              <h4 className="text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-800 uppercase leading-tight">
                REPÚBLICA BOLIVARIANA DE VENEZUELA
              </h4>
              <h5 className="text-[9px] sm:text-[10px] font-semibold text-slate-700 uppercase leading-tight">
                MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA
              </h5>
              <h3 className="text-xs sm:text-sm font-extrabold text-unerg-blue uppercase tracking-tight leading-snug mt-0.5">
                UNIVERSIDAD NACIONAL EXPERIMENTAL DE LOS LLANOS CENTRALES "RÓMULO GALLEGOS"
              </h3>
              <p className="text-[9px] text-slate-500 italic">
                Área de Conocimiento: {report.area_nombre || teacher?.area_nombre || 'Vicerrectorado Académico'} • Sede: {report.sede_nombre || teacher?.sede_nombre}
              </p>
            </div>
            <div className="w-20 h-20 flex-shrink-0 flex flex-col items-center justify-center p-1 border border-slate-200 rounded bg-slate-50 text-center">
              <QrCode className="w-10 h-10 text-slate-800" />
              <span className="text-[8px] font-mono text-slate-600 mt-0.5 font-bold">VERIF-VALID</span>
            </div>
          </div>
        </div>

        {/* Verification Metadata Bar */}
        <div className="flex items-center justify-between bg-slate-50 p-2.5 rounded-md border border-slate-200 text-[10px] font-mono mb-6">
          <div>
            Nº DE MEMORANDO / REGISTRO: <strong className="text-slate-900">{report.memoNumber}</strong>
          </div>
          <div>
            CÓDIGO DE AUTENTICIDAD: <strong className="text-unerg-blue">{report.verification_code}</strong>
          </div>
          <div>
            FECHA DE EMISIÓN: <strong className="text-slate-900">{report.created_at.split(' ')[0]}</strong>
          </div>
        </div>

        {/* Document Title */}
        <div className="text-center my-6">
          <h2 className="text-lg sm:text-xl font-extrabold uppercase tracking-wide text-slate-900 underline decoration-unerg-gold decoration-2 underline-offset-4">
            {report.typeReport}
          </h2>
          <p className="text-xs text-slate-500 mt-1 uppercase font-semibold">
            DIRECCIÓN DE GESTIÓN Y CONTROL DEL TALENTO HUMANO ACADÉMICO
          </p>
        </div>

        {/* Document Body Content depending on type */}
        <div className="text-xs leading-relaxed text-slate-800 space-y-4 my-6 text-justify">
          {report.typeReport === 'Constancia de Trabajo' && (
            <>
              <p>
                Quien suscribe, por medio de la presente, hace constar que el (la) ciudadano(a):
              </p>
              <div className="bg-slate-50 p-4 rounded-lg border border-slate-200 my-3 text-left">
                <div className="text-sm font-bold text-slate-900">
                  {teacher ? `${teacher.name} ${teacher.surName}` : report.teacher_name}
                </div>
                <div className="text-xs text-slate-600 mt-1 grid grid-cols-1 sm:grid-cols-2 gap-1 font-sans">
                  <div>Cédula de Identidad: <strong className="font-mono text-slate-800">V-{report.teacher_cdi}</strong></div>
                  <div>Categoría en Escalafón: <strong className="text-unerg-blue">{category?.current_category || 'Instructor'}</strong></div>
                  <div>Dedicación Horaria: <strong>{dedication?.name || 'Tiempo Completo'} ({dedication?.hours || 30} horas semanales)</strong></div>
                  <div>Adscripción: <strong>{teacher?.sede_nombre}</strong></div>
                  <div>Área Académica: <strong>{teacher?.area_nombre}</strong></div>
                  <div>Programa Académico: <strong>{teacher?.programa_nombre}</strong></div>
                  <div>Fecha de Ingreso / Ascenso: <strong>{teacher?.datePromotion || '2015-06-15'}</strong></div>
                </div>
              </div>
              <p>
                Presta sus servicios docentes y de investigación en esta Casa de Estudios en calidad de Personal Académico Ordinario, cumpliendo a cabalidad con las obligaciones reglamentarias, carga de docencia directa, asesorías estudiantiles y comisiones institucionales encomendadas.
              </p>
              <p>
                Constancia que se expide a petición de la parte interesada en la ciudad de San Juan de los Morros, a los {currentDate}.
              </p>
            </>
          )}

          {report.typeReport === 'Expediente Curricular Individual' && (
            <>
              <p>
                Informe curricular integral emitido a través del Sistema SIGEDOR con relación al expediente académico del docente:
              </p>
              <div className="border border-slate-200 rounded-lg p-4 bg-slate-50 space-y-2">
                <div className="font-bold text-sm text-unerg-blue">
                  {teacher?.name} {teacher?.surName} — V-{teacher?.cdi}
                </div>
                <div className="grid grid-cols-2 gap-2 text-[11px]">
                  <div>Pregrado: <strong>{category?.preTitle || 'Licenciado'}</strong></div>
                  <div>Posgrado / Doctorado: <strong>{category?.lastTitle || 'Ninguno'}</strong></div>
                  <div>Escalafón Vigente: <strong>{category?.current_category}</strong></div>
                  <div>Carga Horaria: <strong>{dedication?.name} ({dedication?.hours}h)</strong></div>
                  <div>Cargo Administrativo: <strong>{dedication?.director || 'Ninguno'}</strong></div>
                  <div>Estudiantes Tutorados: <strong>{dedication?.studentNumber} alumnos</strong></div>
                </div>
              </div>

              <div className="mt-4">
                <h4 className="font-bold text-slate-900 mb-2 uppercase text-[11px]">Cátedras Asignadas en Semestre Activo:</h4>
                <div className="border border-slate-200 rounded overflow-hidden">
                  <table className="w-full text-left text-[11px]">
                    <thead className="bg-slate-100 border-b font-bold text-slate-700">
                      <tr>
                        <th className="p-2">Cátedra</th>
                        <th className="p-2">Programa</th>
                        <th className="p-2">UC</th>
                        <th className="p-2">Horas</th>
                        <th className="p-2">Secciones</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {teacherSites.map(s => (
                        <tr key={s.id}>
                          <td className="p-2 font-semibold">{s.info}</td>
                          <td className="p-2">{s.programa_nombre}</td>
                          <td className="p-2">{s.uc}</td>
                          <td className="p-2">{s.weekHours}h</td>
                          <td className="p-2">{s.sections}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            </>
          )}

          {report.typeReport === 'Memorando de Carga y Dedicación' && (
            <>
              <p>
                Por medio del presente memorando se notifica formalmente la ratificación y distribución de la carga horaria semanal y responsabilidades académicas asignadas para el periodo correspondiente:
              </p>
              <div className="bg-amber-50/70 border border-amber-200 p-4 rounded-lg text-amber-950 space-y-1">
                <div><strong>Docente:</strong> {teacher?.name} {teacher?.surName} (V-{teacher?.cdi})</div>
                <div><strong>Modalidad de Dedicación:</strong> {dedication?.name} — {dedication?.hours} Horas Semanales</div>
                <div><strong>Cátedra Titular:</strong> {teacher?.asignaturePromotion}</div>
                <div><strong>Responsabilidad Especial:</strong> {dedication?.director || 'Carga docente regular de aula'}</div>
                <div><strong>Atención a Alumnos:</strong> {dedication?.studentHours} horas semanales dedicadas a {dedication?.studentNumber} estudiantes</div>
              </div>
              <p>
                Se exhorta al personal docente a mantener la debida consignación de planes de curso, actas de evaluación continua y cumplimiento de horarios de asesoría.
              </p>
            </>
          )}

          {report.typeReport === 'Reporte Institucional de Escalafón' && (
            <>
              <p>
                Certificación oficial del escalafón universitario y fecha de incorporación al cuerpo de profesores de la Universidad Rómulo Gallegos:
              </p>
              <div className="p-4 bg-slate-50 border border-slate-200 rounded-lg space-y-1.5">
                <div><strong>Docente:</strong> {teacher?.name} {teacher?.surName}</div>
                <div><strong>Categoría:</strong> {category?.current_category}</div>
                <div><strong>Fecha de Promoción / Ascenso:</strong> {teacher?.datePromotion}</div>
                <div><strong>Mención PEII / Acreditación:</strong> {category?.info || 'Activo conforme a estatuto'}</div>
              </div>
            </>
          )}

          <div className="p-3 bg-slate-100/70 rounded-md border border-slate-200 text-[10px] text-slate-600 mt-4">
            <strong>Nota de Autenticidad:</strong> La autenticidad de este documento y las resoluciones aquí expresadas pueden verificarse en el portal de validación documental SIGEDOR de la UNERG introduciendo el código <code>{report.verification_code}</code> o mediante consulta ante la Dirección de Talento Humano.
          </div>
        </div>

        {/* Signatures & Seals */}
        <div className="pt-12 mt-12 border-t border-slate-200 grid grid-cols-3 gap-6 text-center text-[10px]">
          <div>
            <div className="h-14 flex items-end justify-center">
              <span className="font-serif italic text-slate-400 text-xs">Firma Autorizada</span>
            </div>
            <div className="border-t border-slate-400 pt-1 font-bold text-slate-800">
              Prof. César Gómez
            </div>
            <div className="text-slate-500">Rector UNERG</div>
          </div>

          <div className="flex flex-col items-center justify-center">
            <div className="w-16 h-16 rounded-full border-2 border-dashed border-unerg-blue flex items-center justify-center p-1 text-unerg-blue">
              <ShieldCheck className="w-8 h-8 opacity-80" />
            </div>
            <span className="text-[9px] font-bold text-unerg-blue mt-1 uppercase">SELLO OFICIAL UNERG</span>
          </div>

          <div>
            <div className="h-14 flex items-end justify-center">
              <span className="font-serif italic text-slate-400 text-xs">Firma y Rúbrica</span>
            </div>
            <div className="border-t border-slate-400 pt-1 font-bold text-slate-800">
              Dra. Maryori Díaz
            </div>
            <div className="text-slate-500">Directora de Talento Humano</div>
          </div>
        </div>
      </div>
    </div>
  );
};
