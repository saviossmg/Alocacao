<?php





/**
 * Oferta
 *
 * @Table(name="oferta", indexes={@Index(name="fk_Oferta_VwCurso1_idx", columns={"Curso"}), @Index(name="fk_Oferta_Registro1_idx", columns={"DiaSemana"}), @Index(name="fk_Oferta_Registro2_idx", columns={"Turno"})})
 * @Entity
 */
class Oferta
{
    /**
     * @var integer
     *
     * @Column(name="Id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="CodPeriodoletivo", type="string", length=50, nullable=true)
     */
    private $codperiodoletivo;

    /**
     * @var string
     *
     * @Column(name="CodTurma", type="string", length=50, nullable=true)
     */
    private $codturma;

    /**
     * @var string
     *
     * @Column(name="NomeTurma", type="string", length=100, nullable=true)
     */
    private $nometurma;

    /**
     * @var string
     *
     * @Column(name="Contexto", type="string", length=50, nullable=true)
     */
    private $contexto;

    /**
     * @var integer
     *
     * @Column(name="Periodo", type="integer", nullable=true)
     */
    private $periodo;

    /**
     * @var string
     *
     * @Column(name="Disciplina", type="string", length=200, nullable=true)
     */
    private $disciplina;

    /**
     * @var string
     *
     * @Column(name="DescricaoPeriodoLetivo", type="string", length=100, nullable=true)
     */
    private $descricaoperiodoletivo;

    /**
     * @var string
     *
     * @Column(name="HoraInicialA", type="string", length=6, nullable=true)
     */
    private $horainiciala;

    /**
     * @var string
     *
     * @Column(name="HoraInicialB", type="string", length=6, nullable=true)
     */
    private $horainicialb;

    /**
     * @var string
     *
     * @Column(name="IntervaloInicio", type="string", length=6, nullable=true)
     */
    private $intervaloinicio;

    /**
     * @var string
     *
     * @Column(name="HoraFinalA", type="string", length=6, nullable=true)
     */
    private $horafinala;

    /**
     * @var string
     *
     * @Column(name="HoraFinalB", type="string", length=6, nullable=true)
     */
    private $horafinalb;

    /**
     * @var string
     *
     * @Column(name="IntervaloFinal", type="string", length=6, nullable=true)
     */
    private $intervalofinal;

    /**
     * @var string
     *
     * @Column(name="ProfessorTitular", type="string", length=100, nullable=true)
     */
    private $professortitular;

    /**
     * @var string
     *
     * @Column(name="TipoProfessor", type="string", length=2, nullable=true)
     */
    private $tipoprofessor;

    /**
     * @var \Registro
     *
     * @ManyToOne(targetEntity="Registro")
     * @JoinColumns({
     *   @JoinColumn(name="DiaSemana", referencedColumnName="Id")
     * })
     */
    private $diasemana;

    /**
     * @var \Registro
     *
     * @ManyToOne(targetEntity="Registro")
     * @JoinColumns({
     *   @JoinColumn(name="Turno", referencedColumnName="Id")
     * })
     */
    private $turno;

    /**
     * @var \Vwcurso
     *
     * @ManyToOne(targetEntity="Vwcurso")
     * @JoinColumns({
     *   @JoinColumn(name="Curso", referencedColumnName="Id")
     * })
     */
    private $curso;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codperiodoletivo
     *
     * @param string $codperiodoletivo
     *
     * @return Oferta
     */
    public function setCodperiodoletivo($codperiodoletivo)
    {
        $this->codperiodoletivo = $codperiodoletivo;

        return $this;
    }

    /**
     * Get codperiodoletivo
     *
     * @return string
     */
    public function getCodperiodoletivo()
    {
        return $this->codperiodoletivo;
    }

    /**
     * Set codturma
     *
     * @param string $codturma
     *
     * @return Oferta
     */
    public function setCodturma($codturma)
    {
        $this->codturma = $codturma;

        return $this;
    }

    /**
     * Get codturma
     *
     * @return string
     */
    public function getCodturma()
    {
        return $this->codturma;
    }

    /**
     * Set nometurma
     *
     * @param string $nometurma
     *
     * @return Oferta
     */
    public function setNometurma($nometurma)
    {
        $this->nometurma = $nometurma;

        return $this;
    }

    /**
     * Get nometurma
     *
     * @return string
     */
    public function getNometurma()
    {
        return $this->nometurma;
    }

    /**
     * Set contexto
     *
     * @param string $contexto
     *
     * @return Oferta
     */
    public function setContexto($contexto)
    {
        $this->contexto = $contexto;

        return $this;
    }

    /**
     * Get contexto
     *
     * @return string
     */
    public function getContexto()
    {
        return $this->contexto;
    }

    /**
     * Set periodo
     *
     * @param integer $periodo
     *
     * @return Oferta
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return integer
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set disciplina
     *
     * @param string $disciplina
     *
     * @return Oferta
     */
    public function setDisciplina($disciplina)
    {
        $this->disciplina = $disciplina;

        return $this;
    }

    /**
     * Get disciplina
     *
     * @return string
     */
    public function getDisciplina()
    {
        return $this->disciplina;
    }

    /**
     * Set descricaoperiodoletivo
     *
     * @param string $descricaoperiodoletivo
     *
     * @return Oferta
     */
    public function setDescricaoperiodoletivo($descricaoperiodoletivo)
    {
        $this->descricaoperiodoletivo = $descricaoperiodoletivo;

        return $this;
    }

    /**
     * Get descricaoperiodoletivo
     *
     * @return string
     */
    public function getDescricaoperiodoletivo()
    {
        return $this->descricaoperiodoletivo;
    }

    /**
     * Set horainiciala
     *
     * @param string $horainiciala
     *
     * @return Oferta
     */
    public function setHorainiciala($horainiciala)
    {
        $this->horainiciala = $horainiciala;

        return $this;
    }

    /**
     * Get horainiciala
     *
     * @return string
     */
    public function getHorainiciala()
    {
        return $this->horainiciala;
    }

    /**
     * Set horainicialb
     *
     * @param string $horainicialb
     *
     * @return Oferta
     */
    public function setHorainicialb($horainicialb)
    {
        $this->horainicialb = $horainicialb;

        return $this;
    }

    /**
     * Get horainicialb
     *
     * @return string
     */
    public function getHorainicialb()
    {
        return $this->horainicialb;
    }

    /**
     * Set intervaloinicio
     *
     * @param string $intervaloinicio
     *
     * @return Oferta
     */
    public function setIntervaloinicio($intervaloinicio)
    {
        $this->intervaloinicio = $intervaloinicio;

        return $this;
    }

    /**
     * Get intervaloinicio
     *
     * @return string
     */
    public function getIntervaloinicio()
    {
        return $this->intervaloinicio;
    }

    /**
     * Set horafinala
     *
     * @param string $horafinala
     *
     * @return Oferta
     */
    public function setHorafinala($horafinala)
    {
        $this->horafinala = $horafinala;

        return $this;
    }

    /**
     * Get horafinala
     *
     * @return string
     */
    public function getHorafinala()
    {
        return $this->horafinala;
    }

    /**
     * Set horafinalb
     *
     * @param string $horafinalb
     *
     * @return Oferta
     */
    public function setHorafinalb($horafinalb)
    {
        $this->horafinalb = $horafinalb;

        return $this;
    }

    /**
     * Get horafinalb
     *
     * @return string
     */
    public function getHorafinalb()
    {
        return $this->horafinalb;
    }

    /**
     * Set intervalofinal
     *
     * @param string $intervalofinal
     *
     * @return Oferta
     */
    public function setIntervalofinal($intervalofinal)
    {
        $this->intervalofinal = $intervalofinal;

        return $this;
    }

    /**
     * Get intervalofinal
     *
     * @return string
     */
    public function getIntervalofinal()
    {
        return $this->intervalofinal;
    }

    /**
     * Set professortitular
     *
     * @param string $professortitular
     *
     * @return Oferta
     */
    public function setProfessortitular($professortitular)
    {
        $this->professortitular = $professortitular;

        return $this;
    }

    /**
     * Get professortitular
     *
     * @return string
     */
    public function getProfessortitular()
    {
        return $this->professortitular;
    }

    /**
     * Set tipoprofessor
     *
     * @param string $tipoprofessor
     *
     * @return Oferta
     */
    public function setTipoprofessor($tipoprofessor)
    {
        $this->tipoprofessor = $tipoprofessor;

        return $this;
    }

    /**
     * Get tipoprofessor
     *
     * @return string
     */
    public function getTipoprofessor()
    {
        return $this->tipoprofessor;
    }

    /**
     * Set diasemana
     *
     * @param \Registro $diasemana
     *
     * @return Oferta
     */
    public function setDiasemana(\Registro $diasemana = null)
    {
        $this->diasemana = $diasemana;

        return $this;
    }

    /**
     * Get diasemana
     *
     * @return \Registro
     */
    public function getDiasemana()
    {
        return $this->diasemana;
    }

    /**
     * Set turno
     *
     * @param \Registro $turno
     *
     * @return Oferta
     */
    public function setTurno(\Registro $turno = null)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return \Registro
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set curso
     *
     * @param \Vwcurso $curso
     *
     * @return Oferta
     */
    public function setCurso(\Vwcurso $curso = null)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return \Vwcurso
     */
    public function getCurso()
    {
        return $this->curso;
    }
}

