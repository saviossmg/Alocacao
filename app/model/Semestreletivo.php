<?php





/**
 * Semestreletivo
 *
 * @Table(name="semestreletivo", indexes={@Index(name="fk_alocacao_semestreLetivo1_idx", columns={"Semestre"}), @Index(name="fk_alocacao_vwCurso1_idx", columns={"Curso"})})
 * @Entity
 */
class Semestreletivo
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
     * @var \Vwsemestre
     *
     * @ManyToOne(targetEntity="Vwsemestre")
     * @JoinColumns({
     *   @JoinColumn(name="Semestre", referencedColumnName="Id")
     * })
     */
    private $semestre;

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
     * Set semestre
     *
     * @param \Vwsemestre $semestre
     *
     * @return Semestreletivo
     */
    public function setSemestre(\Vwsemestre $semestre = null)
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * Get semestre
     *
     * @return \Vwsemestre
     */
    public function getSemestre()
    {
        return $this->semestre;
    }

    /**
     * Set curso
     *
     * @param \Vwcurso $curso
     *
     * @return Semestreletivo
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

