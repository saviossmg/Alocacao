<?php
/**
 * VwMatricula
 *
 * @Table(name="VwMatricula", indexes={@Index(name="fk_VwDisciplina_has_VwAluno_VwAluno1_idx", columns={"IdAluno"}), @Index(name="fk_VwDisciplina_has_VwAluno_VwDisciplina1_idx", columns={"IdDisciplina"}), @Index(name="fk_VwMatricula_SemestreLetivo1_idx", columns={"IdSemestreLetivo"})})
 * @Entity
 */
class Vwmatricula
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
     * @var \Vwaluno
     *
     * @ManyToOne(targetEntity="Vwaluno")
     * @JoinColumns({
     *   @JoinColumn(name="IdAluno", referencedColumnName="Id")
     * })
     */
    private $idaluno;

    /**
     * @var \Vwdisciplina
     *
     * @ManyToOne(targetEntity="Vwdisciplina")
     * @JoinColumns({
     *   @JoinColumn(name="IdDisciplina", referencedColumnName="Id")
     * })
     */
    private $iddisciplina;

    /**
     * @var \Semestreletivo
     *
     * @ManyToOne(targetEntity="Semestreletivo")
     * @JoinColumns({
     *   @JoinColumn(name="IdSemestreLetivo", referencedColumnName="Id")
     * })
     */
    private $idsemestreletivo;


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
     * Set idaluno
     *
     * @param \Vwaluno $idaluno
     *
     * @return Vwmatricula
     */
    public function setIdaluno(\Vwaluno $idaluno = null)
    {
        $this->idaluno = $idaluno;

        return $this;
    }

    /**
     * Get idaluno
     *
     * @return \Vwaluno
     */
    public function getIdaluno()
    {
        return $this->idaluno;
    }

    /**
     * Set iddisciplina
     *
     * @param \Vwdisciplina $iddisciplina
     *
     * @return Vwmatricula
     */
    public function setIddisciplina(\Vwdisciplina $iddisciplina = null)
    {
        $this->iddisciplina = $iddisciplina;

        return $this;
    }

    /**
     * Get iddisciplina
     *
     * @return \Vwdisciplina
     */
    public function getIddisciplina()
    {
        return $this->iddisciplina;
    }

    /**
     * Set idsemestreletivo
     *
     * @param \Semestreletivo $idsemestreletivo
     *
     * @return Vwmatricula
     */
    public function setIdsemestreletivo(\Semestreletivo $idsemestreletivo = null)
    {
        $this->idsemestreletivo = $idsemestreletivo;

        return $this;
    }

    /**
     * Get idsemestreletivo
     *
     * @return \Semestreletivo
     */
    public function getIdsemestreletivo()
    {
        return $this->idsemestreletivo;
    }
}

